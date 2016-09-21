<?php
namespace CodeDocs\Processor;

use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use CodeDocs\Topic;

/**
 * Creates files from Topic-Annotations
 */
class CreateFilesFromTopics implements ProcessorInterface
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
        $exportPath = $state->config->getExportDir() . '/';

        foreach ($state->annotations as $annotation) {
            if ($annotation instanceof Topic) {
                if (!$annotation->file) {
                    continue;
                }

                $file = $exportPath . $annotation->file;

                $filesystem->writeFile($file, $annotation->content);
            }
        }
    }
}
