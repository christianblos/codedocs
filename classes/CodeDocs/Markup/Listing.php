<?php
namespace CodeDocs\Markup;

use CodeDocs\ListItem;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\ContentList;
use CodeDocs\ValueObject\ItemList;
use CodeDocs\ValueObject\Parsable;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @CodeDocs\Topic(file="02.usage/02.markups/00.Listing/docs.md")
 *
 * The **Listing**-Markup should be used together with the
 * [**ListItem**-Annotation](/usage/annotations/ListItem).
 * It will display all ListItems in a list.
 *
 * ## Usage
 *
 * ```md
 * Listing("someList", glue=", ", linked=true, contents=true)
 * ```
 *
 * ## Parameters
 *
 * The first param is the name of the list.
 *
 * ##### glue
 *
 * Will be used as separator for the list. If no glue is given, the list will be
 * displayed in bullet points.
 *
 * ##### linked
 *
 * Set this to true to link all ListItems. Only ListItems with a **link**-property will
 * be linked (See [ListItem](/usage/annotations/ListItem)).
 *
 * ##### contents
 *
 * Set this to true to show the ListItem contents instead of the labels.
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/markups/Listing/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/markups/Listing/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/markups/Listing/export/example.md")}
 * ```
 *
 * @CodeDocs\ListItem(list="markups", label="Listing", link="/usage/markups/Listing")
 *
 * @Annotation
 */
class Listing extends Markup
{
    /**
     * @Required
     * @var string
     */
    public $value;

    /**
     * @var string|null
     */
    public $glue;

    /**
     * @var bool
     */
    public $linked = false;

    /**
     * @var bool
     */
    public $contents = false;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        $listItems = $parseResult->getAnnotations()
            ->filter(function ($annotation) {
                return $annotation instanceof ListItem && $annotation->list === $this->value;
            })
            ->map(function (ListItem $annotation) {
                if ($this->contents === true) {
                    return $annotation->content;
                }

                return $this->getLabel($annotation);
            });

        if ($this->contents) {
            return (string)new ContentList($listItems, $this->glue);
        }

        return (string)new ItemList($listItems, $this->glue);
    }

    /**
     * @param ListItem $listItem
     *
     * @return string
     */
    private function getLabel(ListItem $listItem)
    {
        if ($listItem->label !== null) {
            $label = $listItem->label;
        } elseif ($listItem->originMethod) {
            $label = $listItem->originClass . '::' . $listItem->originMethod;
        } elseif ($listItem->originProperty) {
            $label = $listItem->originClass . '::$' . $listItem->originProperty;
        } else {
            $label = $listItem->originClass;
        }

        if ($this->linked && $listItem->link) {
            $label = sprintf('[%s](%s)', $label, $listItem->link);
        }

        return $label;
    }
}
