<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Replaces strings in the given text.
 */
class Replace extends MarkupFunction
{
    const FUNC_NAME = 'replace';

    /**
     * @param string   $text  The text where to replace strings
     * @param string[] $using The replacements (Key = search, Value = replacement)
     *
     * @return \string[]
     */
    public function __invoke($text, array $using)
    {
        foreach ($using as $search => $replace) {
            $text = str_replace($search, $replace, $text);
        }

        return $text;
    }
}
