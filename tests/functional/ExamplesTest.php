<?php
namespace CodeDocs\Test\Functional;

class ExamplesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider examplesProvider
     */
    public function examples_are_correct($example)
    {
        $rootDir    = realpath(__DIR__ . '/../..');
        $bin        = $rootDir . '/codedocs.php';
        $configPath = $rootDir . '/examples/' . $example . '/config.yaml';

        $command = sprintf('php %s %s', $bin, $configPath);
        exec($command, $output, $exitCode);

        $this->assertEquals(0, $exitCode);

        $expectedFile = __DIR__ . '/expectedExampleOutputs/' . $example . '.md';
        $exportedFile = $rootDir . '/examples/' . $example . '/export/example.md';
        $this->assertFileEquals($expectedFile, $exportedFile);
    }

    /**
     *
     */
    public function examplesProvider()
    {
        $rootDir = realpath(__DIR__ . '/../..');

        $annotations = array_map(
            function ($path) {
                return 'annotations/' . substr($path, strrpos($path, '/') + 1, -4);
            },
            glob($rootDir . '/classes/CodeDocs/*.php')
        );

        $markups = array_map(
            function ($path) {
                return 'markups/' . substr($path, strrpos($path, '/') + 1, -4);
            },
            array_filter(
                glob($rootDir . '/classes/CodeDocs/Markup/*.php'),
                function ($path) use ($rootDir) {
                    // base Markup class does not have an example
                    return $path !== $rootDir . '/classes/CodeDocs/Markup/Markup.php';
                }
            )
        );

        return array_map(
            function ($example) {
                return [$example];
            },
            array_merge($annotations, $markups)
        );
    }
}
