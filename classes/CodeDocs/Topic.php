<?php
namespace CodeDocs;

use CodeDocs\Annotation\Annotation;
use CodeDocs\Annotation\ContentInterface;

/**
 * @Annotation
 *
 * @Topic(file="02.usage/01.annotations/00.Topic/docs.md")
 *
 * ---
 * title: Topic-Annotation
 * taxonomy:
 *     category: docs
 * ---
 *
 * Normally, you create the markdown documentation files in the docs folder.
 * But with the **Topic** Annotation together with the
 * [CreateFilesFromTopics-Processor](/usage/processors/CreateFilesFromTopics),
 * you can also define them in your classes.
 *
 * ## Usage
 *
 * ```md
 * CodeDocs\Topic(file="some/documentation.md")
 *
 * ... content ...
 * ```
 *
 * or
 *
 *
 * ```md
 * CodeDocs\Topic(id="some-documentation")
 *
 * ... content ...
 * ```
 *
 * ## Parameters
 *
 * ##### file
 *
 * The file that will be created inside the docs directory.
 *
 * ##### id
 *
 * The id can be used as reference for the [TopicContent-Markup](/usage/markups/TopicContent)
 *
 * ##### content
 *
 * The whole text below the annotation will be the content of that topic.
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/annotations/Topic/classes/example.php")}
 * ```
 *
 * The generated file will have this content:
 *
 * ```md
 * {@FileContent("examples/annotations/Topic/export/example.md")}
 * ```
 *
 *
 * @ListItem(list="annotations", link="/usage/annotations/Topic")
 */
class Topic extends Annotation implements ContentInterface
{
    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $content;

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }
}
