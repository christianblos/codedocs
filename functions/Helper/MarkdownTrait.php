<?php
namespace CodeDocs\Func\Helper;

trait MarkdownTrait
{
    /**
     * @param array $headlines
     * @param array $rows
     *
     * @return string
     */
    protected function renderMarkdownTable(array $headlines, array $rows)
    {
        $lines = [];
        foreach ($headlines as $headline) {
            $lines[] = str_repeat('-', strlen($headline));
        }

        $headlines = array_map([$this, 'escapePipe'], $headlines);

        $content = '| ' . implode(' | ', $headlines) . PHP_EOL;
        $content .= '| ' . implode(' | ', $lines) . PHP_EOL;

        foreach ($rows as $row) {
            $row = array_map([$this, 'escapePipe'], $row);
            $content .= '| ' . implode(' | ', $row) . PHP_EOL;
        }

        return $content;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function escapePipe($value)
    {
        return str_replace('|', '\\|', $value);
    }
}
