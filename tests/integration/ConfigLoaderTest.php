<?php
namespace CodeDocs\Test\Integration;

use CodeDocs\Config;
use CodeDocs\ConfigLoader;
use PHPUnit\Framework\TestCase;

class ConfigLoaderTest extends TestCase
{
    /**
     * @var ConfigLoader
     */
    private $configLoader;

    protected function setUp()
    {
        $this->configLoader = new ConfigLoader();
    }

    public function testLoadDefaultConfig()
    {
        $config = $this->configLoader->load(__DIR__ . '/configs', []);

        $expected           = new Config();
        $expected->buildDir = 'build';
        $expected->params   = ['default' => true];

        self::assertEquals($expected, $config);
    }

    public function testLoadCustomConfig()
    {
        $config = $this->configLoader->load(__DIR__, [__DIR__ . '/configs/config1.php']);

        $expected           = new Config();
        $expected->buildDir = 'build1';
        $expected->params   = ['first' => 1];

        self::assertEquals($expected, $config);
    }

    public function testLoadMultipleConfigs()
    {
        $config = $this->configLoader->load(__DIR__ . '/configs', [
            'config1.php',
            'config2.php',
        ]);

        $expected           = new Config();
        $expected->buildDir = 'build2';
        $expected->params   = [
            'first'  => 1,
            'second' => 2,
        ];

        self::assertEquals($expected, $config);
    }
}
