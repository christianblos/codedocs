<?php
namespace CodeDocs\Markup;

use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\ItemList;
use CodeDocs\ValueObject\Parsable;
use Doctrine\Common\Annotations\Annotation\Required;
use ReflectionClass;

/**
 * @CodeDocs\Topic(file="02.usage/03.markups/00.ConstantListing/docs.md")
 *
 * ---
 * title: ConstantListing-Markup
 * taxonomy:
 *     category: docs
 * ---
 *
 * The **ConstantListing**-Markup will display a list of class constants matching the given criteria.
 *
 * ## Usage
 *
 * ```md
 * ConstantListing(class="SomeClass", matches="/^TYPE_/", glue=", ")
 * ```
 *
 * ## Parameters
 *
 * ##### class (required)
 *
 * The class which contains the constants.
 *
 * ##### matches
 *
 * Only constants matching this regular expression will be displayed.
 *
 * ##### glue
 *
 * Will be used as separator for the list. If no glue is given, the list will be displayed in bullet points.
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/ConstantListing/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/ConstantListing/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/ConstantListing/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="ConstantListing", link="/usage/markups/ConstantListing")
 *
 * @Annotation
 */
class ConstantListing extends Markup
{
    /**
     * @Required
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $matches;

    /**
     * @var string|null
     */
    public $glue;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $ref = new ReflectionClass($this->class);

        $constants = array_keys($ref->getConstants());

        if ($this->matches) {
            $constants = array_filter($constants, function ($name) {
                return preg_match($this->matches, $name) > 0;
            });
        }

        return (string)new ItemList($constants, $this->glue);
    }
}
