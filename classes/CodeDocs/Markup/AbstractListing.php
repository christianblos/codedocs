<?php
namespace CodeDocs\Markup;

/**
 * Contains functionalities for lists
 */
abstract class AbstractListing extends Markup
{

    /**
     * @var string|null
     */
    public $glue;

    /**
     * @param string[] $items
     *
     * @return string
     */
    protected function combineItems(array $items)
    {
        if ($this->glue === null) {
            $prefix = '- ';
            $glue   = "\n- ";
        } else {
            $prefix = '';
            $glue   = $this->glue;
        }

        return $prefix . implode($glue, $items);
    }

    /**
     * @param string[] $contents
     *
     * @return string
     */
    protected function combineContents(array $contents)
    {
        $glue = $this->glue === null ? "\n\n" : $this->glue;

        return implode($glue, $contents);
    }
}
