<?php
namespace CodeDocs;

use CodeDocs\Annotation\Annotation;
use CodeDocs\Annotation\ContentInterface;
use CodeDocs\Markup\FileContent;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 *
 * @Topic(file="02.usage/01.annotations/00.ListItem/docs.md")
 *
 * The **ListItem** Annotation can be used to assign an item to a list.
 * It will be used together with the [**Listing**-Markup](/usage/markups/Listing).
 *
 * This is useful if you have a list in the documentation whose items are in different classes.
 *
 *
 * ## Usage
 *
 * ```md
 * CodeDocs\ListItem(list="someList", label="The label", link="/some/url")
 * ```
 *
 * ## Parameters
 *
 * ##### list (required)
 *
 * The name of the associated list.
 *
 * ##### label
 *
 * The displayed item text.
 *
 * ##### link
 *
 * If the list is linkable, you can define the url for this item here.
 *
 * ##### content
 *
 * The whole text below the annotation will be the content of that item.
 *
 *
 *
 * ## Example
 *
 * Example code:
 *
 * ```php
 * {@FileContent("examples/annotations/ListItem/classes/example.php")}
 * ```
 *
 * Example documentation:
 *
 * ```md
 * {@FileContent("examples/annotations/ListItem/docs/example.md")}
 * ```
 *
 * The result will look like this:
 *
 * ```md
 * {@FileContent("examples/annotations/ListItem/export/example.md")}
 * ```
 *
 *
 * @ListItem(list="annotations", link="/usage/annotations/ListItem")
 */
class ListItem extends Annotation implements ContentInterface
{
    /**
     * @Required
     * @var string
     */
    public $list;

    /**
     * @var mixed
     */
    public $label;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $content;

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
