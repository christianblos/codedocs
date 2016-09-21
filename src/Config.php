<?php
namespace CodeDocs;

class Config
{
    /**
     * Base directory of your project.
     * Must be absolute path!
     *
     * @var string
     */
    public $baseDir;

    /**
     * The directory where all generated files are stored.
     * Relative to the baseDir if starting with "./".
     *
     * @var string
     */
    public $buildDir = './build';

    /**
     * Location of the documentations.
     * Relative to the baseDir if starting with "./".
     *
     * @var string|null
     */
    public $docsDir;

    /**
     * A list of locations where the parser looks for classes.
     * All these classes are available in Markups.
     * Relative to the baseDir if starting with "./".
     *
     * @var string[]
     */
    public $classDirs = [];

    /**
     * If configured, caching is enabled and all cache files are stored here.
     *
     * @var string|null
     */
    public $cacheDir;

    /**
     * Extra parameters that can be used e.g. in markups.
     *
     * @var array
     */
    public $params = [];

    /**
     * You can register your namespaces for annotations here. This allows you to omit the namespace in the annotations.
     * For example `@MyNamespace\Something`. If you register "MyNamespace", you can just use `@Something`.
     *
     * @var string[]
     */
    public $annotationNamespaces = [];

    /**
     * If you want to use custom functions in your documentation, you can register them here.
     * The array key must be the function name and the value must be a callable.
     * To make use of the CodeDocs State object in your callable, it must extend \CodeDocs\Doc\MarkupFunction.
     *
     * @var callable[]
     */
    public $functions = [];

    /**
     * Processors which are executed before Markups are replaced.
     *
     * @var ProcessorInterface[]
     */
    public $preProcessors = [];

    /**
     * Processors which are executed after Markups are replaced.
     *
     * @var ProcessorInterface[]
     */
    public $postProcessors = [];

    /**
     * @return string
     */
    public function getExportDir()
    {
        return $this->buildDir . '/export';
    }
}
