<?php
namespace CodeDocs\Processor;

use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use CodeDocs\Tag;

/**
 * Runs all configured Post-Processors
 *
 * @Tag("defaultProcessor")
 */
class RunPostProcessors implements ProcessorInterface
{
    /**
     * @param State  $state
     * @param Logger $logger
     *
     * @throws \RuntimeException
     */
    public function run(State $state, Logger $logger)
    {
        $logger->log(0, 'run post processors');

        foreach ($state->config->getPostProcessors() as $processor) {
            $logger->log(1, get_class($processor));
            $processor->run($state, $logger);
        }
    }
}
