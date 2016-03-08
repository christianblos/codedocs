<?php
namespace CodeDocs\Component;

use CodeDocs\Collection\AnnotationList;
use CodeDocs\Collection\ClassList;
use CodeDocs\Markup\Markup;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\Processor\Processor;
use CodeDocs\ValueObject\Parsable;

class App
{

    /**
     * @var string
     */
    private $configReader;

    /**
     * @var AnnotationParser
     */
    private $annotationParser;

    /**
     * @var MarkupParser
     */
    private $markupParser;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var OutputLogger
     */
    private $logger;

    /**
     * @param ConfigReader     $configReader
     * @param AnnotationParser $annotationParser
     * @param MarkupParser     $markupParser
     * @param Tokenizer        $tokenizer
     * @param Filesystem       $filesystem
     * @param OutputLogger     $logger
     */
    public function __construct(
        ConfigReader $configReader,
        AnnotationParser $annotationParser,
        MarkupParser $markupParser,
        Tokenizer $tokenizer,
        Filesystem $filesystem,
        OutputLogger $logger
    ) {
        $this->configReader     = $configReader;
        $this->annotationParser = $annotationParser;
        $this->markupParser     = $markupParser;
        $this->tokenizer        = $tokenizer;
        $this->filesystem       = $filesystem;
        $this->logger           = $logger;
    }

    /**
     * Run application
     */
    public function run()
    {
        $config = $this->configReader->getConfig();

        $this->mountPlugins($config);

        $this->registerAnnotationNamespaces($config);

        $this->cleanUpExportDir($config);

        foreach ($config->getSources() as $source) {
            $this->handleSource($config, $source);
        }

        $this->logger->log(0, 'run finalizers...');

        foreach ($config->getFinalizers() as $finalizer) {
            $this->logger->log(1, 'run ' . get_class($finalizer));
            $finalizer->run($config);
        }

        $this->logger->log(0, 'done!');
    }

    /**
     * @param Config $config
     */
    private function mountPlugins(Config $config)
    {
        $this->logger->log(0, 'mount plugins...');

        foreach ($config->getPlugins() as $plugin) {
            $this->logger->log(1, 'mount plugins ' . get_class($plugin));
            $plugin->mount($config);
        }
    }

    /**
     * @param Config $config
     */
    private function cleanUpExportDir(Config $config)
    {
        $this->logger->log(0, 'clean up export dir...');

        $this->filesystem->purge($config->getExportDir());
        $this->filesystem->ensureDir($config->getExportDir());
    }

    /**
     * Register custom annotation namespaces
     *
     * @param Config $config
     */
    private function registerAnnotationNamespaces(Config $config)
    {
        foreach ($config->getAnnotationNamespacePaths() as $namespace => $path) {
            $this->annotationParser->registerNamespace($namespace, $path);
        }
    }

    /**
     * @param Config $config
     * @param Source $source
     */
    private function handleSource(Config $config, Source $source)
    {
        $this->logger->log(0, 'handle source ' . $source->getBaseDir());

        $docsDir = $source->getDocsDir();
        if ($docsDir !== null) {
            $this->logger->log(1, 'move markdown files from docs to export...');
            $this->filesystem->mirror($docsDir, $config->getExportDir());
        }

        $classDirs = $source->getClassDirs();
        if ($classDirs) {
            $this->logger->log(1, 'search and include classes...');
            $classes = $this->includeClasses($classDirs);
        } else {
            $classes = [];
        }

        $this->logger->log(1, 'extract annotations...');
        $annotationList = $this->extractAnnotations($classes);

        $parseResult = new ParseResult($annotationList, new ClassList($classes));

        $this->logger->log(1, 'run pre processors...');
        $this->runProcessors(Processor::TYPE_PRE, $config, $parseResult, $source);

        $this->logger->log(1, 'replace markups...');
        $this->replaceMarkups($config, $parseResult, $source);

        $this->logger->log(1, 'run post processors...');
        $this->runProcessors(Processor::TYPE_POST, $config, $parseResult, $source);
    }

    /**
     * Search all classes in source
     *
     * @param array $dirs
     *
     * @return array
     */
    private function includeClasses(array $dirs)
    {
        $files = $this->filesystem->getFilesOfDir($dirs, '/\.php$/');

        $allClasses = [];

        foreach ($files as $file) {
            $filepath = $file->getPathname();
            $classes  = $this->tokenizer->getClassesOfFile($filepath);

            foreach ($classes as $class) {
                $this->logger->log(2, 'found class ' . $class . ' in file ' . $filepath);
                $allClasses[] = $class;
            }

            if ($classes) {
                require_once $filepath;
            }
        }

        return $allClasses;
    }

    /**
     * @param array $classes
     *
     * @return AnnotationList
     */
    private function extractAnnotations(array $classes)
    {
        $annotationList = new AnnotationList();

        foreach ($classes as $class) {
            $this->logger->log(2, '...of class ' . $class);

            $annotations = $this->annotationParser->extractAnnotations($class);

            foreach ($annotations as $annotation) {
                $this->logger->log(3, 'found ' . get_class($annotation));
            }

            $annotationList->addMulti($annotations);
        }

        return $annotationList;
    }

    /**
     * Run given processors
     *
     * @param string      $type
     * @param Config      $config
     * @param ParseResult $parseResult
     * @param Source      $source
     */
    private function runProcessors($type, Config $config, ParseResult $parseResult, Source $source)
    {
        $processors = $config->getProcessors($type)->toArray();

        foreach ($processors as $processor) {
            $this->logger->log(2, 'run ' . get_class($processor));
            $processor->run($parseResult, $config, $source);
        }
    }

    /**
     * Parse md files in cache dir and replace markups
     *
     * @param Config      $config
     * @param ParseResult $parseResult
     * @param Source      $source
     */
    private function replaceMarkups(Config $config, ParseResult $parseResult, Source $source)
    {
        $namespaces = $config->getMarkupNamespaces();
        foreach ($namespaces as $namespace) {
            $this->markupParser->addMarkupNamespace($namespace);
        }

        $files = $this->filesystem->getFilesOfDir([$config->getExportDir()], '/\.md$/');

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $this->logger->log(2, 'replace markups in ' . $filePath);

            $fileContent = file_get_contents($filePath);
            $fileContent = $this->replaceMarkupsInContent($fileContent, $parseResult, $config, $source);

            $fileObject = $file->openFile('w+');
            $fileObject->fwrite($fileContent);
        }
    }

    /**
     * @param string      $content
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return string
     */
    private function replaceMarkupsInContent($content, ParseResult $parseResult, Config $config, Source $source)
    {
        $markups = $this->markupParser->getMarkups($content);

        foreach ($markups as $markup) {
            $this->logger->log(3, 'replace markup ' . $markup->getMarkupString());

            $this->replaceConfigParamsInMarkup($markup, $config);

            $replace = $markup->buildContent($parseResult, $config, $source);

            if ($replace instanceof Parsable) {
                $replace = $this->replaceMarkupsInContent((string)$replace, $parseResult, $config, $source);
            }

            $content = str_replace($markup->getMarkupString(), $replace, $content);
        }

        return $content;
    }

    /**
     * @param Markup $markup
     * @param Config $config
     */
    private function replaceConfigParamsInMarkup(Markup $markup, Config $config)
    {
        foreach ($markup as $key => $value) {
            if (is_string($value) && preg_match('/^%(.*)%$/', $value, $matches)) {
                $this->logger->log(3, 'replace config param ' . $value);
                $markup->$key = $config->getParam($matches[1]);
            }
        }
    }
}
