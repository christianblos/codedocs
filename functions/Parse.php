<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Type\Parsable;

/**
 * Parse a string to replace markups.
 */
class Parse extends MarkupFunction
{
    const FUNC_NAME = 'parse';

    /**
     * @param string $text The text to parse
     *
     * @return Parsable
     */
    public function __invoke($text)
    {
        return new Parsable($text);
    }
}
