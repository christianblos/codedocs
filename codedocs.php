<?php

$autoloaderFiles = [
    __DIR__ . '/../autoload.php',
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../../autoload.php',
    'autoload.php',
];

try {
    $loaded = false;

    foreach ($autoloaderFiles as $autoloader) {
        if (file_exists($autoloader)) {
            require_once $autoloader;
            $loaded = true;
            break;
        }
    }

    if (!$loaded) {
        throw new \Exception('Can not find composer autoloader. Do you have installed it correctly?');
    }

    if (isset($argv[1])) {
        $configFile = realpath($argv[1]);
    } else {
        $configFile = realpath(getcwd() . '/codedocs.json');
    }

    if (!$configFile) {
        throw new \Exception('invalid config file');
    }

    $parsedown = new ParsedownExtra();
    $builder = new \CodeDocs\Builder($parsedown, $configFile);
    $builder->build();

    echo 'Generated successfully!' . PHP_EOL;

} catch (Exception $ex) {
    echo 'Error: ' . $ex->getMessage() . PHP_EOL;
    exit(1);
}
