<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\Collection\ProcessorList;
use CodeDocs\Component\Plugin;
use CodeDocs\Model\Config;
use CodeDocs\Model\Source;
use CodeDocs\Processor\Processor;

/**
 * @covers \CodeDocs\Model\Config
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    private $config;

    /**
     *
     */
    protected function setUp()
    {
        $this->config = new Config('buildDir', 'configDir');
    }

    /**
     * @test
     */
    public function returns_build_dir()
    {
        $this->assertEquals('buildDir', $this->config->getBuildDir());
    }

    /**
     * @test
     */
    public function returns_config_dir()
    {
        $this->assertEquals('configDir', $this->config->getConfigDir());
    }

    /**
     * @test
     */
    public function returns_export_dir()
    {
        $this->assertEquals('buildDir/export', $this->config->getExportDir());
    }

    /**
     * @test
     */
    public function contains_sources()
    {
        $source = $this->getMockBuilder(Source::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config->addSource($source);

        $this->assertSame($source, $this->config->getSources()[0]);
    }

    /**
     * @test
     */
    public function contains_params()
    {
        $this->config->setParam('foo', 'bar');

        $this->assertEquals('bar', $this->config->getParam('foo'));
    }

    /**
     * @test
     */
    public function returns_null_for_not_existing_params()
    {
        $this->assertNull($this->config->getParam('foo'));
    }

    /**
     * @test
     */
    public function contains_processors()
    {
        $processor = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->config->addProcessor('pre', $processor);

        $list = $this->config->getProcessors('pre');
        $this->assertInstanceOf(ProcessorList::class, $list);
        $this->assertSame($processor, $list->toArray()[0]);
    }

    /**
     * @test
     */
    public function returns_empty_list_for_not_existing_processor_type()
    {
        $list = $this->config->getProcessors('foo');
        $this->assertInstanceOf(ProcessorList::class, $list);
        $this->assertCount(0, $list->toArray());
    }

    /**
     * @test
     */
    public function contains_annotation_namespace_paths()
    {
        $this->config->setAnnotationNamespacePath('namespace', 'path');

        $this->assertEquals('path', $this->config->getAnnotationNamespacePaths()['namespace']);
    }

    /**
     * @test
     */
    public function contains_markup_namespaces()
    {
        $this->config->addMarkupNamespace('namespace');

        $this->assertEquals('namespace', $this->config->getMarkupNamespaces()[0]);
    }

    /**
     * @test
     */
    public function contains_plugins()
    {
        $plugin = $this->getMockBuilder(Plugin::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->config->addPlugin($plugin);

        $this->assertSame($plugin, $this->config->getPlugins()[0]);
    }

}
