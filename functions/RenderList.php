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
 * | Name   | Type     | Description
 * | ------ | -------- | ------------
 * | of     | string[] | The list items
 * | prefix | string   | (Optional) The list prefix
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
     * @param string[]    $of
     * @param string|null $prefix
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
