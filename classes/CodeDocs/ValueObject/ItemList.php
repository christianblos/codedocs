<?php
namespace CodeDocs\ValueObject;

class ItemList
{
    /**
     * @var array|\string[]
     */
    private $items;

    /**
     * @var string|null
     */
    private $glue;

    /**
     * @param string[]    $items
     * @param string|null $glue
     */
    public function __construct(array $items, $glue = null)
    {
        $this->items = $items;
        $this->glue  = $glue;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->glue === null) {
            $prefix = '- ';
            $glue   = "\n- ";
        } else {
            $prefix = '';
            $glue   = $this->glue;
        }

        return $prefix . implode($glue, $this->items);
    }
}
