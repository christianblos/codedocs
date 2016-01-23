<?php
namespace CodeDocs\Test\Component;

use CodeDocs\Component\ConfigReader;
use CodeDocs\Component\Plugin;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\Processor\Processor;

/**
 * @covers \CodeDocs\Component\ConfigReader
 */
class ConfigReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Config
     */
    private function getFullConfig()
    {
        $configReader = new ConfigReader(__DIR__ . '/fixtures/fullConfig.yaml');

        return $configReader->getConfig();
    }

    /**
     * @return Config
     */
    private function getMinConfig()
    {
        $configReader = new ConfigReader(__DIR__ . '/fixtures/minConfig.yaml');

        return $configReader->getConfig();
    }

    /**
     * @test
     */
    public function can_read_build_dir()
    {
        $config = $this->getFullConfig();
        $this->assertEquals(__DIR__ . '/fixtures/./build', $config->getBuildDir());
    }

    /**
     * @test
     */
    public function returns_default_build_dir()
    {
        $config = $this->getMinConfig();
        $this->assertEquals(__DIR__ . '/fixtures/build', $config->getBuildDir());
    }

    /**
     * @test
     */
    public function sets_config_dir_by_config_file()
    {
        $config = $this->getFullConfig();
        $this->assertEquals(__DIR__ . '/fixtures', $config->getConfigDir());
    }

    /**
     * @test
     */
    public function can_read_sources()
    {
        $config = $this->getFullConfig();

        $source = $config->getSources()[0];

        $this->assertEquals(__DIR__ . '/fixtures', $source->getBaseDir());
        $this->assertEquals(__DIR__ . '/fixtures/./docs', $source->getDocsDir());
        $this->assertEquals(__DIR__ . '/fixtures/./classes', $source->getClassDirs()[0]);
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage no source defined in config
     */
    public function fails_if_no_sources_defined()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/noSourcesConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage sources config must be an array
     */
    public function fails_if_sources_is_no_array()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/sourcesNotArrayConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage source config at position 0 must be an array
     */
    public function fails_if_source_is_no_array()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/sourceNotArrayConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage no baseDir configured in source config at position 0
     */
    public function fails_if_source_has_no_base_dir()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/sourceNoBaseDirConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage classDirs in source config (position 0) must be an array
     */
    public function fails_if_source_has_invalid_class_dirs()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/sourceInvalidClassesDirConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     */
    public function can_read_params()
    {
        $config = $this->getFullConfig();
        $this->assertEquals('someVal', $config->getParam('someKey'));
    }

    /**
     * @test
     */
    public function can_read_processors_without_params()
    {
        $config = $this->getFullConfig();

        $processors = $config->getProcessors('pre');
        $processor  = $processors->toArray()[0];

        $this->assertInstanceOf(SomeProcessor::class, $processor);
    }

    /**
     * @test
     */
    public function can_read_processors_wit_params()
    {
        $config = $this->getFullConfig();

        $processors = $config->getProcessors('post');
        $processor  = $processors->toArray()[0];

        $this->assertInstanceOf(SomeProcessor::class, $processor);
        $this->assertEquals('val', $processor->get('key'));
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     * @expectedExceptionMessage config for pre processors must be an array
     */
    public function fails_if_processors_is_not_array()
    {
        $reader = new ConfigReader(__DIR__ . '/fixtures/invalidProcessorsConfig.yaml');
        $reader->getConfig();
    }

    /**
     * @test
     */
    public function can_read_annotation_namespace_paths()
    {
        $config = $this->getFullConfig();
        $this->assertEquals(['My\Annotation' => 'path'], $config->getAnnotationNamespacePaths());
    }

    /**
     * @test
     */
    public function can_read_markup_namespaces()
    {
        $config = $this->getFullConfig();
        $this->assertEquals(['My\Markup'], $config->getMarkupNamespaces());
    }

    /**
     * @test
     */
    public function can_read_plugins_without_parameters()
    {
        $config = $this->getFullConfig();

        $plugins = $config->getPlugins();

        $this->assertInstanceOf(SomePlugin::class, $plugins[0]);
        $this->assertNull($plugins[0]->get('pluginKey'));
    }

    /**
     * @test
     */
    public function can_read_plugins_with_parameters()
    {
        $config = $this->getFullConfig();

        $plugins = $config->getPlugins();

        $this->assertInstanceOf(SomePlugin::class, $plugins[1]);
        $this->assertEquals('pluginVal', $plugins[1]->get('pluginKey'));
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     */
    public function fails_if_config_not_exists()
    {
        new ConfigReader(__DIR__ . '/fixtures/nope.yaml');
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\ConfigException
     */
    public function fails_if_config_is_empty()
    {
        new ConfigReader(__DIR__ . '/fixtures/emptyConfig.yaml');
    }

}

class SomeProcessor extends Processor
{
    public function run(ParseResult $parseResult, Config $config, Source $source)
    {
    }

    public function get($name)
    {
        return $this->getParam($name);
    }
}

class SomePlugin extends Plugin
{
    public function mount(Config $config)
    {
    }

    public function get($name)
    {
        return $this->getParam($name);
    }
}
