<?php
namespace CodeDocs\Component;

use AppendIterator;
use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Markup\Markup;
use CodeDocs\Processor\Processor;
use CodeDocs\ValueObject\Parsable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SplFileInfo;

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
     * @return ConfigReader
     */
    public function getConfigReader()
    {
        return $this->configReader;
    }

    /**
     * @return string
     */
    public function getExportDir()
    {
        return $this->configReader->getBuildDir() . '/export';
    }

    /**
     * Run application
     */
    public function run()
    {
        $this->logger->log(0, 'clean up export dir...');
        $this->cleanExportDir();

        $this->registerAnnotationNamespaces();

        foreach ($this->configReader->getSources() as $source) {
            $this->logger->log(0, 'handle source ' . $source->baseDir);

            if ($source->docsDir !== null) {
                $this->logger->log(1, 'move markdown files from docs to export...');
                $this->filesystem->mirror($source->docsDir, $this->getExportDir());
            }

            if ($source->classDirs) {
                $this->logger->log(1, 'search and include classes...');
                $classes = $this->includeClasses($source->classDirs);
            } else {
                $classes = [];
            }

            $this->logger->log(1, 'extract annotations...');
            $annotationList = $this->extractAnnotations($classes);

            $parseResult = new ParseResult($annotationList, $classes);

            $this->logger->log(1, 'run pre processors...');
            $this->runProcessors($this->configReader->getProcessors('pre'), $parseResult, $source);

            $this->logger->log(1, 'replace markups...');
            $this->replaceMarkups($parseResult, $source);

            $this->logger->log(1, 'run post processors...');
            $this->runProcessors($this->configReader->getProcessors('post'), $parseResult, $source);
        }

        $this->logger->log(0, 'done!');
    }

    /**
     * Clean up export dir or create it
     */
    private function cleanExportDir()
    {
        $exportDir = $this->getExportDir();

        if ($this->filesystem->exists($exportDir)) {
            $this->filesystem->purge($exportDir);
        } else {
            $this->filesystem->mkdir($exportDir);
        }
    }

    /**
     * Register custom annotation namespaces
     */
    private function registerAnnotationNamespaces()
    {
        $rootDir = $this->configReader->getConfigRootDir();

        foreach ($this->configReader->getAnnotationNamespaces() as $namespace => $path) {
            $this->annotationParser->registerNamespace($namespace, $rootDir . DIRECTORY_SEPARATOR . $path);
        }
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
        $files = $this->getFilesOfDir($dirs, '/\.php$/');

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
     * @param Processor[] $processors
     * @param ParseResult $parseResult
     * @param Source      $source
     */
    private function runProcessors(array $processors, ParseResult $parseResult, Source $source)
    {
        $config = new Config($this, $source);

        foreach ($processors as $processor) {
            $this->logger->log(2, 'run ' . get_class($processor));
            $processor->run($parseResult, $config);
        }
    }

    /**
     * Parse md files in cache dir and replace markups
     *
     * @param ParseResult $parseResult
     * @param Source      $source
     */
    private function replaceMarkups(ParseResult $parseResult, Source $source)
    {
        $namespaces = $this->configReader->getMarkupNamespaces();
        foreach ($namespaces as $namespace) {
            $this->markupParser->addMarkupNamespace($namespace);
        }

        $config = new Config($this, $source);

        $files = $this->getFilesOfDir([$this->getExportDir()], '/\.md$/');

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $this->logger->log(2, 'replace markups in ' . $filePath);

            $fileContent = file_get_contents($filePath);
            $fileContent = $this->replaceMarkupsInContent($fileContent, $parseResult, $config);

            $fileObject = $file->openFile('w+');
            $fileObject->fwrite($fileContent);
        }
    }

    /**
     * @param string      $content
     * @param ParseResult $parseResult
     * @param Config      $config
     *
     * @return string
     */
    private function replaceMarkupsInContent($content, ParseResult $parseResult, Config $config)
    {
        $markups = $this->markupParser->getMarkups($content);

        foreach ($markups as $markup) {
            $this->logger->log(3, 'replace markup ' . $markup->getMarkupString());

            $this->replaceConfigParamsInMarkup($markup);

            $replace = $markup->buildContent($parseResult, $config);

            if ($replace instanceof Parsable) {
                $replace = $this->replaceMarkupsInContent((string)$replace, $parseResult, $config);
            }

            $content = str_replace($markup->getMarkupString(), $replace, $content);
        }

        return $content;
    }

    /**
     * @param Markup $markup
     */
    private function replaceConfigParamsInMarkup(Markup $markup)
    {
        foreach ($markup as $key => $value) {
            if (preg_match('/^%(.*)%$/', $value, $matches)) {
                $this->logger->log(3, 'replace config param ' . $value);
                $markup->$key = $this->configReader->getParam($matches[1]);
            }
        }
    }

    /**
     * @param array  $dirs
     * @param string $match
     *
     * @return SplFileInfo[]
     */
    private function getFilesOfDir(array $dirs, $match)
    {
        $iterator = new AppendIterator();

        foreach ($dirs as $dir) {
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)));
        }

        return new RegexIterator($iterator, $match);
    }
}
