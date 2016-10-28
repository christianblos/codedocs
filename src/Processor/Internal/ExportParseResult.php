<?php
namespace CodeDocs\Processor\Internal;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use RuntimeException;

/**
 * Exports the parse results to a file
 */
class ExportParseResult implements ProcessorInterface
{
    const DEFAULT_FILE = 'parseResult.txt';

    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct($file = null)
    {
        $this->file = $file ?: self::DEFAULT_FILE;
    }

    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws RuntimeException
     * @throws MarkupException
     */
    public function run(State $state, Logger $logger)
    {
        $state->annotations;
        $state->classes;

        $dir = realpath($state->config->getExportDir());
        if (!$dir) {
            throw new RuntimeException('export dir does not exist');
        }

        $exportFile = $dir . '/' . $this->file;

        $logger->log(0, sprintf('save parsed result to %s', $exportFile));

        $content = print_r($state->classes, true) . PHP_EOL . print_r($state->annotations, true);

        file_put_contents($exportFile, $content);
    }
}
