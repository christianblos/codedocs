<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * @CodeDocs\Topic(file="functions/list.md")
 *
 * Renders a list of the given array
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\RenderList::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ list(of:classes(extends: 'SomeClass')) }}
 * \{{ list(of:['A', 'B', 'C'], prefix:'# ') }}
 * ```
 */
class RenderList extends MarkupFunction
{
    const FUNC_NAME = 'list';

    /**
     * @param string[]    $of     The list items
     * @param string|null $prefix The list prefix
     *
     * @return string
     */
    public function __invoke(array $of, $prefix = null)
    {
        if ($prefix === null) {
            $prefix = '- ';
        }

        return array_map(
            function ($item) use ($prefix) {
                return $prefix . $item;
            },
            $of
        );
    }
}
