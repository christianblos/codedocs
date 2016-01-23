<?php
namespace CodeDocs\Markup;

use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Parsable;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.ConfigParam/docs.md")
 *
 * ---
 * title: ConfigParam-Markup
 * taxonomy:
 *     category: docs
 * ---
 *
 * The **ConfigParam**-Markup will display the value of a param in the
 * {@ConfigParam("configFile")} configuration file.
 *
 * ## Usage
 *
 * ```md
 * ConfigParam("name")
 * ```
 *
 * The given parameter must be the name of the configuration param.
 *
 *
 * ## Example
 *
 * Example config:
 *
 * ```php
 * {@FileContent("examples/markups/ConfigParam/config.yaml")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/ConfigParam/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/ConfigParam/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="ConfigParam", link="/usage/markups/ConfigParam")
 *
 * @Annotation
 */
class ConfigParam extends Markup
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
        return $config->getParam($this->value);
    }
}
