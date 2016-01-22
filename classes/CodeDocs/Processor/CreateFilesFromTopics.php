<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\Config;
use CodeDocs\Component\Filesystem;
use CodeDocs\Component\ParseResult;
use CodeDocs\ListItem;
use CodeDocs\Topic;

/**
 * @Topic(file="02.usage/03.processors/00.CreateFilesFromTopics/docs.md")
 *
 * This processor should be added as **pre** processor in your {@ConfigParam("configFile")}:
 *
 * ```yaml
 * processors:
 *   pre:
 *     - \CodeDocs\Processor\CreateFilesFromTopics
 * ```
 *
 * It will use all [Topic-Annotations](/usage/annotations/Topic) with the file param set
 * to generate the documentation files out of it.
 *
 *
 * @ListItem(list="processors", link="/usage/processors/CreateFilesFromTopics")
 */
class CreateFilesFromTopics extends Processor
{

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     */
    public function run(ParseResult $parseResult, Config $config)
    {
        $exportPath = $config->getExportDir() . DIRECTORY_SEPARATOR;

        /** @var Topic[] $topics */
        $topics = $parseResult->getAnnotations()->filterByClass(Topic::class)->toArray();

        $filesystem = new Filesystem();

        foreach ($topics as $topic) {
            if (!$topic->file) {
                continue;
            }

            $file = $exportPath . $topic->file;
            $dir  = dirname($file);

            $filesystem->ensureDir($dir);

            file_put_contents($file, $topic->content);
        }
    }
}
