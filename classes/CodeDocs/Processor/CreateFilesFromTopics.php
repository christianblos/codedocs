<?php
namespace CodeDocs\Processor;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;
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
class CreateFilesFromTopics implements ProcessorInterface
{

    /**
     * @param AnnotationList $annotationList
     * @param Config         $config
     */
    public function run(AnnotationList $annotationList, Config $config)
    {
        $exportPath = $config->getExportDir() . DIRECTORY_SEPARATOR;

        /** @var Topic[] $topics */
        $topics = $annotationList->filterByClass(Topic::class)->toArray();

        foreach ($topics as $topic) {
            if (!$topic->file) {
                continue;
            }

            $file = $exportPath . $topic->file;
            $dir  = dirname($file);

            if (!is_dir($dir)) {
                mkdir(dirname($file), 0777, true);
            }

            file_put_contents($file, $topic->content);
        }
    }
}
