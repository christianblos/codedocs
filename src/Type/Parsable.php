<?php
namespace CodeDocs\Type;

class Parsable
{
    public $text;

    /**
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }
}
