<?php
namespace CodeDocs\Processor;

use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use CodeDocs\Tag;

/**
 * Copies all docs to a temporary directory
 *
 * @Tag("defaultProcessor")
 */
class MirrorDocs implements ProcessorInterface
{
    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws \RuntimeException
     */
    public function run(State $state, Logger $logger)
    {
        $filesystem = new Filesystem();

        $docsDir = $state->config->getDocsDir();

        if ($docsDir === null) {
            $logger->log(0, 'skip copying docs to export dir (no docs dir configured)');

            return;
        }

        $docsDir = realpath($docsDir);
        if ($docsDir === false) {
            $logger->log(0, 'skip copying docs to export dir (docs dir docs dir does not exist)');

            return;
        }

        $exportDir = $state->config->getExportDir();

        $logger->log(0, sprintf('copy docs to export dir (%s -> %s)', $docsDir, $exportDir));
        $filesystem->mirror($docsDir, $exportDir);
    }
}
