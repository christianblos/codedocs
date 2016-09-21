<?php
/** @var \CodeDocs\Config $config */

$config->baseDir = __DIR__;

$config->buildDir = './build';

$config->docsDir = './docs-src';

$config->classDirs = ['src', 'annotations', 'functions'];

$config->cacheDir = '/tmp/codedocs';

$config->preProcessors = [
    new \CodeDocs\Processor\CreateFilesFromTopics(),
];

$config->postProcessors = [
    new \CodeDocs\Processor\CopyExportFiles('docs'),
];
