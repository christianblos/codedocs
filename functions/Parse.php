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
 * {{ methodParamsTable(of: '\CodeDocs\Func\Parse::__invoke') }}
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
     * @param string $text The text to parse
     *
     * @return Parsable
     */
    public function __invoke($text)
    {
        return new Parsable($text);
    }
}
