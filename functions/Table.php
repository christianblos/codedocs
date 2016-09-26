<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func\Helper\MarkdownTrait;

/**
 * @CodeDocs\Topic(file="functions/table.md")
 *
 * Creates a markdown table.
 *
 * #### Parameters
 *
 * | Name | Type     | Description
 * | ---- | -------- | ------------
 * | of   | string[] | The items (A row is created per item)
 * | cols | string[] | Key = column name, Value column content ('__item__' is replaced by the actual item value)
 *
 * #### Example
 *
 * ```
 * \{{parse(text:table(
 *   of: classes(extends:'\CodeDocs\Doc\MarkupFunction'),
 *   cols: [
 *     'Name'        => '{{ defaultValue(of:"__item__::FUNC_NAME") }}()',
 *     'Description' => '{{ docComment(of:"__item__", firstLine: true) }}',
 *   ]
 * ))}}
 * ```
 */
class Table extends MarkupFunction
{
    use MarkdownTrait;

    const FUNC_NAME = 'table';

    /**
     * @param array $of
     * @param array $cols
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke(array $of, array $cols)
    {
        $headlines = array_keys($cols);
        $rows      = [];

        foreach ($of as $item) {
            $values = [];

            foreach ($cols as $col) {
                $values[] = str_replace('__item__', $item, $col);
            }

            $rows[] = $values;
        }

        return $this->renderMarkdownTable($headlines, $rows);
    }
}
