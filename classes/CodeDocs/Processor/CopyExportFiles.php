<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\Filesystem;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Directory;

/**
 * @deprecated
 */
class CopyExportFiles extends Processor
{
    /**
     * @var bool
     */
    private $isPurged = false;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     */
    public function run(ParseResult $parseResult, Config $config, Source $source)
    {
        $dest = $this->getMandatoryParam('dest');

        $dest = new Directory($dest, $config->getBuildDir());

        $filesystem = new Filesystem();

        if ($this->isPurged === false && $this->getParam('purge')) {
            $filesystem->purge($dest);
            $this->isPurged = true;
        }

        $filesystem->copy($config->getExportDir(), $dest);
    }
}
