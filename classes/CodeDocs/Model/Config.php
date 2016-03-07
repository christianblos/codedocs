<?php
namespace CodeDocs\Model;

use CodeDocs\Collection\ProcessorList;
use CodeDocs\Component\Plugin;
use CodeDocs\Finalizer\Finalizer;
use CodeDocs\Processor\Processor;

class Config
{
    /**
     * @var string
     */
    private $buildDir;

    /**
     * @var string
     */
    private $configDir;

    /**
     * @var Source[]
     */
    private $sources = [];

    /**
     * @var mixed[]
     */
    private $params = [];

    /**
     * @var ProcessorList[]
     */
    private $processorLists = [];

    /**
     * @var Finalizer[]
     */
    private $finalizers = [];

    /**
     * @var string[]
     */
    private $annotationNamespaces = [];

    /**
     * @var string[]
     */
    private $markupNamespaces = [];

    /**
     * @var Plugin[]
     */
    private $plugins = [];

    /**
     * @param string $buildDir
     * @param string $configDir
     */
    public function __construct($buildDir, $configDir)
    {
        $this->buildDir  = $buildDir;
        $this->configDir = $configDir;
    }

    /**
     * @return string
     */
    public function getBuildDir()
    {
        return $this->buildDir;
    }

    /**
     * @return string
     */
    public function getConfigDir()
    {
        return $this->configDir;
    }

    /**
     * @return string
     */
    public function getExportDir()
    {
        return $this->getBuildDir() . '/export';
    }

    /**
     * @param Source $source
     */
    public function addSource(Source $source)
    {
        $this->sources[] = $source;
    }

    /**
     * @return Source[]
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param string    $type
     * @param Processor $processor
     */
    public function addProcessor($type, Processor $processor)
    {
        if (!array_key_exists($type, $this->processorLists)) {
            $this->processorLists[$type] = new ProcessorList();
        }

        $this->processorLists[$type]->add($processor);
    }

    /**
     * @param string $type
     *
     * @return ProcessorList
     */
    public function getProcessors($type)
    {
        if (array_key_exists($type, $this->processorLists)) {
            return $this->processorLists[$type];
        }

        return new ProcessorList();
    }

    /**
     * @param Finalizer $finalizer
     */
    public function addFinalizer(Finalizer $finalizer)
    {
        $this->finalizers[] = $finalizer;
    }

    /**
     * @return Finalizer[]
     */
    public function getFinalizers()
    {
        return $this->finalizers;
    }

    /**
     * @param string $namespace
     * @param string $path
     */
    public function setAnnotationNamespacePath($namespace, $path)
    {
        $this->annotationNamespaces[$namespace] = $path;
    }

    /**
     * @return string[]
     */
    public function getAnnotationNamespacePaths()
    {
        return $this->annotationNamespaces;
    }

    /**
     * @param string $namespace
     */
    public function addMarkupNamespace($namespace)
    {
        $this->markupNamespaces[] = $namespace;
    }

    /**
     * @return string[]
     */
    public function getMarkupNamespaces()
    {
        return $this->markupNamespaces;
    }

    /**
     * @param Plugin $plugin
     */
    public function addPlugin(Plugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    /**
     * @return Plugin[]
     */
    public function getPlugins()
    {
        return $this->plugins;
    }
}
