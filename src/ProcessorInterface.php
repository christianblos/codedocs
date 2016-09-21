<?php
namespace CodeDocs;

interface ProcessorInterface
{
    /**
     * @param State  $state
     * @param Logger $logger
     */
    public function run(State $state, Logger $logger);
}
