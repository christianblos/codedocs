<?php
namespace CodeDocs\Component;

use CodeDocs\Exception\ConfigException;
use CodeDocs\Processor\Processor;
use CodeDocs\ValueObject\Directory;
use Symfony\Component\Yaml\Yaml;

class ConfigReader
{
    /**
     * @var string
     */
    private $configFile;

    /**
     * @var string
     */
    private $configRootDir;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configFile    = $configFile;
        $this->configRootDir = dirname($configFile);

        $this->config = Yaml::parse(file_get_contents($configFile));
        if (!is_array($this->config)) {
            throw new ConfigException('invalid config file');
        }
    }

    /**
     * @return string
     */
    public function getConfigRootDir()
    {
        return $this->configRootDir;
    }

    /**
     * @return Directory
     */
    public function getBuildDir()
    {
        if (isset($this->config['buildDir'])) {
            $buildDir = $this->config['buildDir'];
        } else {
            $buildDir = $this->configRootDir . DIRECTORY_SEPARATOR . 'build';
        }

        return new Directory($buildDir, $this->configRootDir);
    }

    /**
     * @param string $key
     *
     * @return Processor[]
     */
    public function getProcessors($key)
    {
        if (!isset($this->config['processors'][$key])) {
            return [];
        }

        $classes = $this->config['processors'][$key];

        if (!is_array($classes)) {
            throw new ConfigException('config for ' . $key . ' processors must be an array');
        }

        $processors = [];

        foreach ($classes as $key => $value) {
            if (is_array($value)) {
                $class  = array_keys($value)[0];
                $params = $value[$class];
            } else {
                $class  = $value;
                $params = [];
            }

            if (!class_exists($class)) {
                throw new ConfigException('processor class ' . $class . ' does not exist');
            }

            $processor = new $class($params);

            if (!$processor instanceof Processor) {
                throw new ConfigException(sprintf(
                    'processor class %s must be a %s',
                    $class,
                    Processor::class
                ));
            }

            $processors[] = $processor;
        }

        return $processors;
    }

    /**
     * @return Source[]
     */
    public function getSources()
    {
        if (!isset($this->config['sources'])) {
            throw new ConfigException('no source defined in config');
        }

        $sourcesConfig = $this->config['sources'];

        if (!is_array($sourcesConfig)) {
            throw new ConfigException('sources config must be an array');
        }

        $sources = [];

        foreach ($sourcesConfig as $key => $srcConf) {
            $sources[] = $this->createSourceFromConfig($srcConf, $key);
        }

        return $sources;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        if (isset($this->config['params'][$name])) {
            return $this->config['params'][$name];
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getMarkupNamespaces()
    {
        if (!isset($this->config['markupNamespaces'])) {
            return [];
        }

        if (!is_array($this->config['markupNamespaces'])) {
            return [];
        }

        return $this->config['markupNamespaces'];
    }

    /**
     * @return string[]
     */
    public function getAnnotationNamespaces()
    {
        if (!isset($this->config['annotationNamespaces'])) {
            return [];
        }

        if (!is_array($this->config['annotationNamespaces'])) {
            return [];
        }

        return $this->config['annotationNamespaces'];
    }

    /**
     * @param array $config
     * @param int   $key
     *
     * @return Source
     */
    private function createSourceFromConfig(array $config, $key)
    {
        if (!is_array($config)) {
            throw new ConfigException(sprintf('source config at position %s must be an array', $key));
        }

        if (!isset($config['baseDir'])) {
            throw new ConfigException('no baseDir configured in source config at position ' . $key);
        }

        $source          = new Source();
        $source->baseDir = new Directory($config['baseDir'], $this->configRootDir);

        if (isset($config['docsDir'])) {
            $source->docsDir = new Directory($config['docsDir'], $source->baseDir);
        }

        if (!isset($config['classDirs'])) {
            $config['classDirs'] = [];
        } elseif (!is_array($config['classDirs'])) {
            throw new ConfigException(sprintf('classDirs in source config (position %s) must be an array', $key));
        }

        foreach ($config['classDirs'] as $classDir) {
            $source->classDirs[] = new Directory($classDir, $source->baseDir);
        }

        return $source;
    }
}
