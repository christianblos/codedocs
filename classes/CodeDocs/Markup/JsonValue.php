<?php
namespace CodeDocs\Markup;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;
use CodeDocs\Exception\MarkupException;
use CodeDocs\ValueObject\Parsable;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.JsonValue/docs.md")
 *
 * The **JsonValue**-Markup will extract a value of a json file.
 *
 * ## Usage
 *
 * ```md
 * JsonValue(file="someFile", key="name")
 * ```
 *
 * ## Parameters
 *
 * ##### file (required)
 *
 * The path of the json file relative to the baseDir.
 *
 * ##### key (required)
 *
 * The key of json object.
 *
 *
 * ## Example
 *
 * Example json file:
 *
 * ```json
 * {@FileContent("examples/markups/JsonValue/docs/example.json")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/JsonValue/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/JsonValue/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="JsonValue", link="/usage/markups/JsonValue")
 *
 * @Annotation
 */
class JsonValue extends Markup
{
    /**
     * @Required
     * @var string
     */
    public $file;

    /**
     * @Required
     * @var string
     */
    public $key;

    /**
     * @param AnnotationList $annotationList
     * @param Config         $config
     *
     * @return string|Parsable
     */
    public function buildContent(AnnotationList $annotationList, Config $config)
    {
        $file = $config->getBaseDir() . '/' . $this->file;

        if (!file_exists($file)) {
            throw new MarkupException(sprintf('json file %s does not exist', $this->file));
        }

        $content = file_get_contents($file);
        $data    = json_decode($content, true);

        if ($data === null) {
            throw new MarkupException(sprintf('invalid json file %s', $this->file));
        }

        if (!isset($data[$this->key])) {
            return '';
        }

        return (string)$data[$this->key];
    }
}
