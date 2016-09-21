<?php
namespace CodeDocs;

use CodeDocs\Exception\ConfigException;
use RuntimeException;

class Runner
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @param Config $userConfig
     *
     * @throws ConfigException
     * @throws RuntimeException
     */
    public function run(Config $userConfig)
    {
        $state         = new State();
        $state->config = new RunnerConfig($userConfig);

        foreach ($this->processors as $processor) {
            $processor->run($state, $this->logger);
        }
    }
}
