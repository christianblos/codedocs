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
        passthru($command, $exitCode);

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
        return [
            ['annotations/ListItem'],
            ['annotations/Topic'],
            ['markups/ClassListing'],
            ['markups/ClassValue'],
            ['markups/CodeSnippet'],
            ['markups/ConfigParam'],
            ['markups/ConstantListing'],
            ['markups/FileContent'],
            ['markups/JsonValue'],
            ['markups/Listing'],
            ['markups/TopicContent'],
        ];
    }
}
