<?php
namespace CodeDocs\ValueObject;

class ContentList
{
    /**
     * @var array|\string[]
     */
    private $contents;

    /**
     * @var string|null
     */
    private $glue;

    /**
     * @param string[]    $contents
     * @param string|null $glue
     */
    public function __construct(array $contents, $glue = null)
    {
        $this->contents = $contents;
        $this->glue     = $glue;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $glue = $this->glue === null ? "\n\n" : $this->glue;

        return implode($glue, $this->contents);
    }
}
