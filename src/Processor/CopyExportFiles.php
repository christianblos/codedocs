<?php
namespace CodeDocs\Processor;

use CodeDocs\Exception\MarkupException;
use CodeDocs\Helper\Filesystem;
use CodeDocs\Logger;
use CodeDocs\ProcessorInterface;
use CodeDocs\State;
use RuntimeException;

/**
 * Copies generated doc files to a given directory
 */
class CopyExportFiles implements ProcessorInterface
{
    /**
     * @var string
     */
    private $destination;

    /**
     * @var bool
     */
    private $purge;

    /**
     * @param string $destination
     * @param bool   $purge
     */
    public function __construct($destination, $purge = false)
    {
        $this->destination = $destination;
        $this->purge       = $purge;
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
        $filesystem = new Filesystem();

        $from = realpath($state->config->getExportDir());
        if (!$from) {
            throw new MarkupException('export dir does not exist');
        }

        if (!$this->destination) {
            throw new MarkupException('destination no set');
        }

        $to = $state->config->getBaseDir() . '/' . $this->destination;

        if ($this->purge) {
            $filesystem->purge($to);
        }

        $logger->log(0, sprintf('mirror export dir (%s -> %s)', $from, $to));

        $filesystem->copy($from, $to);
    }
}
