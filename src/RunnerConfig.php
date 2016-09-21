<?php
namespace CodeDocs;

use CodeDocs\Exception\ConfigException;
use CodeDocs\Type\Path;

class RunnerConfig
{
    const EXPORT_DIR = 'export';

    /**
     * @var Config
     */
    private $userConfig;

    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var string
     */
    private $buildDir;

    /**
     * @var string|null
     */
    private $docsDir;

    /**
     * @var string[]
     */
    private $classDirs = [];

    /**
     * @var string|null
     */
    private $cacheDir;

    /**
     * @param Config $config
     *
     * @throws ConfigException
     */
    public function __construct(Config $config)
    {
        $this->userConfig = $config;
        $this->baseDir    = realpath($config->baseDir);

        if (!$this->baseDir) {
            throw new ConfigException('invalid baseDir configured');
        }

        $this->buildDir = $this->convertPath($config->buildDir);
        $this->docsDir  = $this->convertPath($config->docsDir);
        $this->cacheDir = $this->convertPath($config->cacheDir);

        foreach ($config->classDirs as $classDir) {
            $this->classDirs[] = $this->convertPath($classDir);
        }
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @return string
     */
    public function getBuildDir()
    {
        return $this->buildDir;
    }

    /**
     * @return string|null
     */
    public function getDocsDir()
    {
        return $this->docsDir;
    }

    /**
     * @return string[]
     */
    public function getClassDirs()
    {
        return $this->classDirs;
    }

    /**
     * @return string|null
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @return string
     */
    public function getExportDir()
    {
        return $this->getBuildDir() . '/' . self::EXPORT_DIR;
    }

    /**
     * @return string[]
     */
    public function getAnnotationNamespaces()
    {
        return $this->userConfig->annotationNamespaces;
    }

    /**
     * @return callable[]
     */
    public function getFunctions()
    {
        if (!$this->userConfig->functions || !is_array($this->userConfig->functions)) {
            return [];
        }

        return $this->userConfig->functions;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->userConfig->params;
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if (!array_key_exists($name, $this->userConfig->params)) {
            return $default;
        }

        return $this->userConfig->params[$name];
    }

    /**
     * @return ProcessorInterface[]
     */
    public function getPreProcessors()
    {
        return $this->userConfig->preProcessors;
    }

    /**
     * @return ProcessorInterface[]
     */
    public function getPostProcessors()
    {
        return $this->userConfig->postProcessors;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function convertPath($path)
    {
        if ($path === null) {
            return null;
        }

        return (new Path($path, $this->baseDir))->toString();
    }
}
