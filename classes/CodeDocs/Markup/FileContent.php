<?php
namespace CodeDocs\Markup;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;
use CodeDocs\ValueObject\Parsable;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.FileContent/docs.md")
 *
 * The **FileContent**-Markup will extract the content of a file.
 *
 * ## Usage
 *
 * ```md
 * FileContent("someFile")
 * ```
 *
 * The given parameter must be the path of the file relative to the baseDir.
 *
 *
 * ## Example
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/FileContent/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/FileContent/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="FileContent", link="/usage/markups/FileContent")
 *
 * @Annotation
 */
class FileContent extends Markup
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param AnnotationList $annotationList
     * @param Config         $config
     *
     * @return string|Parsable
     */
    public function buildContent(AnnotationList $annotationList, Config $config)
    {
        $file = $config->getBaseDir() . '/' . $this->value;

        if (file_exists($file)) {
            return trim(file_get_contents($file));
        }

        return null;
    }
}
