<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * @CodeDocs\Topic(file="functions/join.md")
 *
 * Joins an array to one string.
 *
 * #### Parameters
 *
 * | Name   | Type     | Description
 * | ------ | -------- | ------------
 * | values | string[] | The values to join
 * | using  | string   | (Optional) The separator
 *
 * #### Example
 *
 * ```
 * \{{ join(values:['one', 'two'], using:', ') }}
 * ```
 */
class Join extends MarkupFunction
{
    const FUNC_NAME = 'join';

    /**
     * @param array  $values
     * @param string $using
     *
     * @return string
     */
    public function __invoke(array $values, $using = '')
    {
        return implode($using, $values);
    }
}
