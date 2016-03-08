<?php
namespace CodeDocs\Markup;

use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Parsable;

/**
 * @CodeDocs\Topic(file="02.usage/03.markups/00.FileContent/docs.md")
 *
 * ---
 * title: FileContent-Markup
 * taxonomy:
 *     category: docs
 * ---
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
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $file = $source->getBaseDir() . '/' . $this->value;

        if (file_exists($file)) {
            return trim(file_get_contents($file));
        }

        return null;
    }
}
