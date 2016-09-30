<?php
namespace CodeDocs\Processor\Internal;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\State;

/**
 * Creates documentation for all markup functions.
 */
class CreateFunctionDocs implements ProcessorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path Path with "%s" as placeholder for function name
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws \RuntimeException
     */
    public function run(State $state, Logger $logger)
    {
        $filesystem = new Filesystem();
        $exportPath = $state->config->getExportDir() . '/';

        foreach ($state->classes as $class) {
            if ($class->extends !== MarkupFunction::class) {
                continue;
            }

            if (!isset($class->constants['FUNC_NAME'])) {
                continue;
            }

            $funcName = $class->constants['FUNC_NAME']->value;

            $doc = $this->createFunctionDoc($class, $funcName, $state);

            $file = $exportPath . sprintf($this->path, $funcName);
            $filesystem->writeFile($file, $doc);
        }
    }

    /**
     * @param RefClass $class
     * @param string   $funcName
     * @param State    $state
     *
     * @return string
     */
    private function createFunctionDoc(RefClass $class, $funcName, State $state)
    {
        $lines = [];

        $lines[] = sprintf('# %s()', $funcName);
        $lines[] = '';

        $lines[] = sprintf(
            '{{ docComment(of: "%s", firstLine: true, excludeAnnotations: true) }}',
            $class->name
        );

        $lines[] = '';
        $lines[] = '### Parameters';
        $lines[] = '';
        $lines[] = sprintf('{{ methodParamsTable(of: "%s::__invoke") }}', $class->name);

        $exampleDir    = sprintf('%s/examples/functions/%s/', $state->config->getBaseDir(), $funcName);
        $exampleClass  = $exampleDir . 'code/SomeClass.php';
        $docSrcFile    = $exampleDir . 'docs-src/doc.md';
        $docResultFile = $exampleDir . 'docs-result/doc.md';
        $filesDir      = $exampleDir . 'files';

        if (file_exists($docSrcFile)) {
            $lines[] = '';
            $lines[] = '### Example';

            if (file_exists($exampleClass)) {
                $lines[] = '';
                $lines[] = 'Source code:';
                $lines[] = '';
                $lines[] = '```';
                $lines[] = sprintf('{{ fileContent(of: relpath(of: "%s")) }}', $exampleClass);
                $lines[] = '```';
            }

            if (is_dir($filesDir)) {
                foreach (new \DirectoryIterator($filesDir) as $fileInfo) {
                    /** @var $fileInfo \SplFileInfo */
                    if (!$fileInfo->isFile()) {
                        continue;
                    }

                    $lines[] = '';
                    $lines[] = $fileInfo->getBasename() . ':';
                    $lines[] = '';
                    $lines[] = '```';
                    $lines[] = sprintf('{{ fileContent(of: relpath(of: "%s")) }}', $fileInfo->getRealPath());
                    $lines[] = '```';
                }
            }

            $lines[] = '';
            $lines[] = 'Documentation source:';
            $lines[] = '';
            $lines[] = '```';
            $lines[] = sprintf('{{ fileContent(of: relpath(of: "%s")) }}', $docSrcFile);
            $lines[] = '```';

            $lines[] = '';
            $lines[] = 'Result:';
            $lines[] = '';
            $lines[] = '```';
            $lines[] = sprintf('{{ fileContent(of: relpath(of: "%s")) }}', $docResultFile);
            $lines[] = '```';

            $lines[] = '';
            $lines[] = sprintf('[See full example code here](../../examples/functions/%s)', $funcName);
        }

        return implode(PHP_EOL, $lines);
    }
}
