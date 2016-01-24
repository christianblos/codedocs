<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\Filesystem;
use CodeDocs\Exception\ConfigException;
use CodeDocs\ListItem;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\Topic;
use CodeDocs\ValueObject\Directory;

/**
 * @Topic(file="02.usage/03.processors/00.CopyExportFiles/docs.md")
 *
 * This processor should be added as **post** processor in your {@ConfigParam("configFile")}:
 *
 * ```yaml
 * processors:
 *   post:
 *     - \CodeDocs\Processor\CopyExportFiles:
 *         dest: ./some/dir
 *         purge: true
 * ```
 *
 * It will copy all exported files to the configured destination. If the dest path
 * starts with ".", it will be relative to the build directory.
 *
 * If you set **purge** to true, it will remove all files in the destination directory on
 * the first run.
 *
 * @ListItem(list="processors", link="/usage/processors/CopyExportFiles")
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
        $dest = $this->getParam('dest');
        if ($dest === null) {
            throw new ConfigException('no dest param given for processor ' . __CLASS__);
        }

        $dest = new Directory($dest, $config->getBuildDir());

        $filesystem = new Filesystem();

        if ($this->isPurged === false && $this->getParam('purge')) {
            $filesystem->purge($dest);
            $this->isPurged = true;
        }

        $filesystem->copy($config->getExportDir(), $dest);
    }
}
