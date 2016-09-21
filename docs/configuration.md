# Configuration

By default, CodeDocs searches for a file named **codedocs.config.php**
in your project's root directory. But you can use another location if you want.

Here's a full configuration sample with descriptions:

```php
<?php
/** @var \CodeDocs\Config $config */

/*
Base directory of your project.
Must be absolute path!
*/
$config->baseDir = __DIR__;

/*
The directory where all generated files are stored.
Relative to the baseDir if starting with "./".
*/
$config->buildDir = './build';

/*
Location of the documentations.
Relative to the baseDir if starting with "./".
*/
$config->docsDir = './docs-src';

/*
A list of locations where the parser looks for classes.
All these classes are available in Markups.
Relative to the baseDir if starting with "./".
*/
$config->classDirs = ['src', 'annotations', 'functions'];

/*
If configured, caching is enabled and all cache files are stored here.
*/
$config->cacheDir = '/tmp/codedocs';

/*
Extra parameters that can be used e.g. in markups.
*/
$config->params = [
    'someParam'   => 'someValue',
    'currentTime' => time(),
];

/*
You can register your namespaces for annotations here. This allows you to omit the namespace in the annotations.
For example `@MyNamespace\Something`. If you register "MyNamespace", you can just use `@Something`.
*/
$config->annotationNamespaces = [
    'MyNamespace',
];

/*
If you want to use custom functions in your documentation, you can register them here.
The array key must be the function name and the value must be a callable.
To make use of the CodeDocs State object in your callable, it must extend \CodeDocs\Doc\MarkupFunction.
*/
$config->functions = [
    'upper' => function ($value) {
        return strtoupper($value);
    },
];

/*
Processors which are executed before Markups are replaced.
*/
$config->preProcessors = [
    new \CodeDocs\Processor\CreateFilesFromTopics(),
];

/*
Processors which are executed after Markups are replaced.
*/
$config->postProcessors = [
    new \CodeDocs\Processor\CopyExportFiles('docs'),
];
```
