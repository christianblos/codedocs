<?php
namespace CodeDocs\Markup;

use CodeDocs\Annotation\Annotation;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\Topic;
use CodeDocs\ValueObject\Parsable;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.TopicContent/docs.md")
 *
 * The **TopicContent**-Markup will include the content of a [Topic-Annotation](/usage/annotations/Topic).
 *
 * ## Usage
 *
 * ```md
 * TopicContent("id-of-topic")
 * ```
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/TopicContent/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/TopicContent/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/TopicContent/export/example.md")}
 * ```
 *
 *
 * @CodeDocs\ListItem(list="markups", label="TopicContent", link="/usage/markups/TopicContent")
 *
 * @Annotation
 */
class TopicContent extends Markup
{

    /**
     * @var string
     */
    public $value;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $annotation = $parseResult->getAnnotations()
            ->filter(function ($annotation) {
                return $annotation instanceof Topic && $annotation->id === $this->value;
            })
            ->getFirst();

        if (!$annotation) {
            throw new MarkupException(sprintf('topic with id "%s" not found', $this->value));
        }

        /** @var Topic $annotation */
        return new Parsable($annotation->content);
    }
}
