<?php
use CodeDocs\Component\AnnotationParser;
use CodeDocs\Component\App;
use CodeDocs\Component\ConfigReader;
use CodeDocs\Component\Filesystem;
use CodeDocs\Component\MarkupParser;
use CodeDocs\Component\OutputLogger;
use CodeDocs\Component\Tokenizer;
use CodeDocs\Exception\ConfigException;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

function includeComposerAutoloader()
{
    $autoloaderFiles = [
        __DIR__ . '/../autoload.php',
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../../autoload.php',
        'autoload.php',
    ];

    foreach ($autoloaderFiles as $autoloader) {
        if (!file_exists($autoloader)) {
            continue;
        }

        require_once $autoloader;

        return true;
    }

    return false;
}

try {
    if (!includeComposerAutoloader()) {
        throw new \Exception('Can not find composer autoloader. Do you have installed it correctly?');
    }

    $isDebug    = false;
    $configFile = realpath(getcwd() . '/codedocs.yaml');

    foreach ($argv as $idx => $arg) {
        if ($arg === '-v') {
            $isDebug = true;
        } elseif ($idx > 0) {
            $configFile = realpath($arg);
        }
    }

    if (!$configFile) {
        throw new ConfigException('config file not found');
    }

    $reader = new SimpleAnnotationReader();
    $reader->addNamespace('');

    $annotationParser = new AnnotationParser($reader);
    $annotationParser->registerNamespace('CodeDocs', __DIR__ . '/classes');

    $app = new App(
        new ConfigReader($configFile),
        $annotationParser,
        new MarkupParser(new DocParser()),
        new Tokenizer(),
        new Filesystem(),
        new OutputLogger($isDebug)
    );

    $app->run();
} catch (Exception $ex) {
    echo 'Error: ' . $ex->getMessage() . PHP_EOL;
    exit(1);
}
