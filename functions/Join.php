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
 * {{ methodParamsTable(of: '\CodeDocs\Func\Join::__invoke') }}
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
     * @param string[] $values The values to join
     * @param string   $using  The separator
     *
     * @return string
     */
    public function __invoke(array $values, $using = '')
    {
        return implode($using, $values);
    }
}
