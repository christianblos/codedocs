<?php
namespace CodeDocs\Test\Integration;

use CodeDocs\Processor\Internal\ExportParseResult;

class ExamplesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider examplesProvider
     */
    public function testFunctionExamples($func)
    {
        $rootDir        = realpath(__DIR__ . '/../..');
        $bin            = $rootDir . '/codedocs.php';
        $configPath     = $rootDir . '/examples/functions/' . $func . '/config.php';
        $testConfigPath = $rootDir . '/tests/integration/configs/examplesTestConfig.php';

        $command = sprintf('php %s %s %s', $bin, $configPath, $testConfigPath);
        exec($command, $output, $exitCode);

        $this->assertEquals(0, $exitCode);

        $expectedFile  = $rootDir . '/examples/functions/' . $func . '/docs-result/doc.md';
        $generatedFile = $rootDir . '/examples/functions/' . $func . '/build/export/doc.md';

        try {
            self::assertFileEquals($expectedFile, $generatedFile);
        } catch (\Exception $ex) {
            $out = 'Expected export file:' . PHP_EOL .
                '```' . PHP_EOL .
                file_get_contents($generatedFile) . PHP_EOL .
                '```' . PHP_EOL .
                PHP_EOL .
                'Parsed result:' . PHP_EOL .
                file_get_contents($rootDir . '/examples/functions/' . $func . '/build/export/' . ExportParseResult::DEFAULT_FILE);

            fwrite(STDERR, $out);

            throw new $ex;
        }
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
