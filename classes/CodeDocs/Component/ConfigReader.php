<?php
namespace CodeDocs\Component;

use CodeDocs\Exception\ConfigException;
use CodeDocs\Model\Config;
use CodeDocs\Model\Source;
use CodeDocs\Processor\Processor;
use CodeDocs\ValueObject\Directory;
use Symfony\Component\Yaml\Yaml;

class ConfigReader
{

    /**
     * @var string
     */
    private $configDir;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configDir = dirname($configFile);

        $this->config = Yaml::parse(file_get_contents($configFile));
        if (!is_array($this->config)) {
            throw new ConfigException('invalid config file');
        }
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        $config = new Config($this->getBuildDir(), $this->configDir);

        $this->addSources($config);
        $this->addParams($config);
        $this->addProcessors($config);
        $this->addAnnotationNamespaces($config);
        $this->addMarkupNamespaces($config);
        $this->addPlugins($config);

        return $config;
    }

    /**
     * @return string
     */
    private function getBuildDir()
    {
        if (array_key_exists('buildDir', $this->config)) {
            $buildDir = $this->config['buildDir'];
        } else {
            $buildDir = $this->configDir . DIRECTORY_SEPARATOR . 'build';
        }

        return (string)new Directory($buildDir, $this->configDir);
    }

    /**
     * @param Config $config
     */
    private function addSources(Config $config)
    {
        if (!array_key_exists('sources', $this->config)) {
            throw new ConfigException('no source defined in config');
        }

        /** @var array $sourcesConfig */
        $sourcesConfig = $this->config['sources'];

        if (!is_array($sourcesConfig)) {
            throw new ConfigException('sources config must be an array');
        }

        foreach ($sourcesConfig as $key => $srcConf) {
            $config->addSource($this->createSourceFromConfig($srcConf, $key));
        }
    }

    /**
     * @param array $config
     * @param int   $key
     *
     * @return Source
     */
    private function createSourceFromConfig($config, $key)
    {
        if (!is_array($config)) {
            throw new ConfigException(sprintf('source config at position %s must be an array', $key));
        }

        if (!array_key_exists('baseDir', $config)) {
            throw new ConfigException('no baseDir configured in source config at position ' . $key);
        }

        $baseDir = (string)new Directory($config['baseDir'], $this->configDir);

        if (array_key_exists('docsDir', $config)) {
            $docsDir = (string)new Directory($config['docsDir'], $baseDir);
        } else {
            $docsDir = null;
        }

        if (!array_key_exists('classDirs', $config) || !$config['classDirs']) {
            $config['classDirs'] = [];
        } elseif (!is_array($config['classDirs'])) {
            throw new ConfigException(sprintf('classDirs in source config (position %s) must be an array', $key));
        }

        $classDirs = [];

        foreach ($config['classDirs'] as $classDir) {
            $classDirs[] = (string)new Directory($classDir, $baseDir);
        }

        return new Source($baseDir, $docsDir, $classDirs);
    }

    /**
     * @param Config $config
     */
    private function addParams(Config $config)
    {
        if (array_key_exists('params', $this->config) && is_array($this->config['params'])) {
            foreach ($this->config['params'] as $name => $value) {
                $config->setParam($name, $value);
            }
        }
    }

    /**
     * @param Config $config
     */
    private function addProcessors(Config $config)
    {
        if (!array_key_exists('processors', $this->config) || !is_array($this->config['processors'])) {
            return;
        }

        foreach ($this->config['processors'] as $type => $processors) {
            if (!is_array($processors)) {
                throw new ConfigException('config for ' . $type . ' processors must be an array');
            }

            $objects = $this->createConfigObjects($processors, Processor::class);
            foreach ($objects as $object) {
                $config->addProcessor($type, $object);
            }
        }
    }

    /**
     * @param Config $config
     */
    private function addAnnotationNamespaces(Config $config)
    {
        if (!array_key_exists('annotationNamespaces', $this->config)) {
            return;
        }

        if (!is_array($this->config['annotationNamespaces'])) {
            return;
        }

        foreach ($this->config['annotationNamespaces'] as $namespace => $path) {
            $config->setAnnotationNamespacePath($namespace, $path);
        }
    }

    /**
     * @param Config $config
     */
    private function addMarkupNamespaces(Config $config)
    {
        if (!array_key_exists('markupNamespaces', $this->config)) {
            return;
        }

        if (!is_array($this->config['markupNamespaces'])) {
            return;
        }

        foreach ($this->config['markupNamespaces'] as $namespace) {
            $config->addMarkupNamespace($namespace);
        }
    }

    /**
     * @param Config $config
     */
    private function addPlugins(Config $config)
    {
        if (!array_key_exists('plugins', $this->config) || !is_array($this->config['plugins'])) {
            return;
        }

        $objects = $this->createConfigObjects($this->config['plugins'], Plugin::class);
        foreach ($objects as $object) {
            $config->addPlugin($object);
        }
    }

    /**
     * @param array  $classConfigs
     * @param string $class
     *
     * @return array
     */
    private function createConfigObjects(array $classConfigs, $class)
    {
        $objects = [];

        foreach ($classConfigs as $classConfig) {
            $object = $this->createObjectOfConfiguredClass($classConfig);

            if (!is_a($object, $class)) {
                throw new ConfigException(sprintf('class %s must be a %s', get_class($object), $class));
            }

            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * @param array|string $classConfig
     *
     * @throws ConfigException
     */
    private function createObjectOfConfiguredClass($classConfig)
    {
        if (is_array($classConfig)) {
            $class  = array_keys($classConfig)[0];
            $params = $classConfig[$class];
        } else {
            $class  = $classConfig;
            $params = [];
        }

        if (!class_exists($class)) {
            throw new ConfigException('class ' . $class . ' does not exist');
        }

        return new $class($params);
    }
}
