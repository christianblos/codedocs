<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Type\Parsable;

/**
 * @CodeDocs\Topic(file="functions/parse.md")
 *
 * Parse a string to replace markups.
 *
 * #### Parameters
 *
 * | Name | Type   | Description
 * | ---- | ------ | ------------
 * | text | string | The text to parse
 *
 * #### Example
 *
 * ```
 * \{{ parse(text:fileContent(of:'someFile')) }}
 * ```
 */
class Parse extends MarkupFunction
{
    const FUNC_NAME = 'parse';

    /**
     * @param string $text
     *
     * @return Parsable
     */
    public function __invoke($text)
    {
        return new Parsable($text);
    }
}
