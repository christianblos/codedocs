<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\Config;
use CodeDocs\Component\Filesystem;
use CodeDocs\Component\ParseResult;
use CodeDocs\Exception\ConfigException;
use CodeDocs\ListItem;
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
 * ```
 *
 * It will copy all exported files to the configured destination. If the dest path
 * starts with ".", it will be relative to the build directory.
 *
 *
 * @ListItem(list="processors", link="/usage/processors/CopyExportFiles")
 */
class CopyExportFiles extends Processor
{

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     */
    public function run(ParseResult $parseResult, Config $config)
    {
        $dest = $this->getParam('dest');
        if ($dest === null) {
            throw new ConfigException('no dest param given for processor ' . __CLASS__);
        }

        $dest = new Directory($dest, $config->getBuildDir());

        $filesystem = new Filesystem();
        $filesystem->mirror($config->getExportDir(), $dest);
    }
}
