<?php
/** @var \CodeDocs\Config $config */

/*
{{ docComment(of:'\CodeDocs\Config::$baseDir', excludeAnnotations: true) }}
*/
$config->baseDir = __DIR__;

/*
{{ docComment(of:'\CodeDocs\Config::$buildDir', excludeAnnotations: true) }}
*/
$config->buildDir = './build';

/*
{{ docComment(of:'\CodeDocs\Config::$docsDir', excludeAnnotations: true) }}
*/
$config->docsDir = './docs-src';

/*
{{ docComment(of:'\CodeDocs\Config::$classDirs', excludeAnnotations: true) }}
*/
$config->classDirs = ['./src', './annotations', './functions'];

/*
{{ docComment(of:'\CodeDocs\Config::$cacheDir', excludeAnnotations: true) }}
*/
$config->cacheDir = '/tmp/codedocs';

/*
{{ docComment(of:'\CodeDocs\Config::$params', excludeAnnotations: true) }}
*/
$config->params = [
    'someParam'   => 'someValue',
    'currentTime' => time(),
];

/*
{{ docComment(of:'\CodeDocs\Config::$annotationNamespaces', excludeAnnotations: true) }}
*/
$config->annotationNamespaces = [
    'MyNamespace',
];

/*
{{ docComment(of:'\CodeDocs\Config::$functions', excludeAnnotations: true) }}
*/
$config->functions = [
    'upper' => function ($value) {
        return strtoupper($value);
    },
];

/*
{{ docComment(of:'\CodeDocs\Config::$preProcessors', excludeAnnotations: true) }}
*/
$config->preProcessors = [
    new \CodeDocs\Processor\CreateFilesFromTopics(),
];

/*
{{ docComment(of:'\CodeDocs\Config::$postProcessors', excludeAnnotations: true) }}
*/
$config->postProcessors = [
    new \CodeDocs\Processor\CopyExportFiles('docs'),
];
