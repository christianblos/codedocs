<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Func\Helper\MarkdownTrait;

/**
 * Creates a markdown table.
 */
class Table extends MarkupFunction
{
    use MarkdownTrait;

    const FUNC_NAME = 'table';

    /**
     * @param string[] $of   The items (A row is created per item)
     * @param string[] $cols Key = column name, Value column content ('%item%' is replaced by the actual item value)
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
                $values[] = str_replace('%item%', $item, $col);
            }

            $rows[] = $values;
        }

        return $this->renderMarkdownTable($headlines, $rows);
    }
}
