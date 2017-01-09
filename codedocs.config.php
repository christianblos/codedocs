<?php
/** @var \CodeDocs\Config $config */

$config->baseDir = __DIR__;

$config->buildDir = './build';

$config->docsDir = './docs-src';

$config->classDirs = ['./src', './annotations', './functions'];

$config->cacheDir = '/tmp/codedocs';

$githubUrl = 'https://github.com/christianblos/codedocs';

$config->preProcessors = [
    new \CodeDocs\Processor\CreateFilesFromTopics(),
    new \CodeDocs\Processor\Internal\CreateFunctionDocs(
        '03.Markups/%s.md',
        $githubUrl . '/tree/master'
    ),
];

$config->postProcessors = [
    new \CodeDocs\Processor\Internal\Mardown2Html('docs', $githubUrl),
];
