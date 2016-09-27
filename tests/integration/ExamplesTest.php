<?php
namespace CodeDocs\Test\Integration;

class ExamplesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider examplesProvider
     */
    public function testFunctionExamples($func)
    {
        $rootDir    = realpath(__DIR__ . '/../..');
        $bin        = $rootDir . '/codedocs.php';
        $configPath = $rootDir . '/examples/functions/' . $func . '/config.php';

        $command = sprintf('php %s %s', $bin, $configPath);
        exec($command, $output, $exitCode);

        $this->assertEquals(0, $exitCode);

        $expectedFile  = $rootDir . '/examples/functions/' . $func . '/docs/doc.md';
        $generatedFile = $rootDir . '/examples/functions/' . $func . '/build/export/doc.md';
        $this->assertFileEquals($expectedFile, $generatedFile, sprintf('check docs of function "%s"', $func));
    }

    public function examplesProvider()
    {
        $rootDir = realpath(__DIR__ . '/../..');

        return array_map(
            function ($path) {
                return [substr($path, strrpos($path, '/') + 1)];
            },
            glob($rootDir . '/examples/functions/*')
        );
    }
}
