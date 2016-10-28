<?php
use CodeDocs\Cli\Input;
use CodeDocs\ConfigLoader;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Logger;
use CodeDocs\Processor\MirrorDocs;
use CodeDocs\Processor\ParseAnnotations;
use CodeDocs\Processor\ParseDocs;
use CodeDocs\Processor\ParseSourceCode;
use CodeDocs\Processor\RunPostProcessors;
use CodeDocs\Processor\RunPreProcessors;
use CodeDocs\Runner;
use Doctrine\Common\Annotations\AnnotationRegistry;

function includeComposerAutoloader()
{
    $autoloaderFiles = [
        __DIR__ . '/../autoload.php',
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../../autoload.php',
        'autoload.php',
    ];

    foreach ($autoloaderFiles as $autoloader) {
        if (file_exists($autoloader)) {
            return require_once $autoloader;
        }
    }

    echo PHP_EOL . 'Error: Can not find composer autoloader. Do you have installed it correctly?' . PHP_EOL;
    exit(1);
}

$loader = includeComposerAutoloader();

$logger = new Logger();

try {
    $input = new Input($argv);

    $logger->setDepth($input->getVerbosity());

    if ($input->getOption('no-color')) {
        $logger->disableColors();
    }

    $configLoader = new ConfigLoader();

    $configFiles = $input->getArguments();
    $config      = $configLoader->load(getcwd(), $configFiles);

    foreach ($input->getParams() as $name => $value) {
        $config->params[$name] = $value;
    }

    AnnotationRegistry::registerLoader(function ($class) use ($loader) {
        /** @var $loader \Composer\Autoload\ClassLoader */
        $loader->loadClass($class);

        if (class_exists($class)) {
            return true;
        }

        return false;
    });

    $runner = new Runner($logger);
    $runner->addProcessor(new ParseSourceCode());
    $runner->addProcessor(new ParseAnnotations());
    $runner->addProcessor(new MirrorDocs());
    $runner->addProcessor(new RunPreProcessors());
    $runner->addProcessor(new ParseDocs());
    $runner->addProcessor(new RunPostProcessors());

    $runner->run($config);
} catch (Exception $ex) {
    $logger->log(-1, PHP_EOL . '<red>Error: ' . $ex->getMessage() . '<reset>');

    if ($ex instanceof MarkupException) {
        $logger->logErrorPosition($ex->getPath(), $ex->getAt());
    }

    exit(1);
}
