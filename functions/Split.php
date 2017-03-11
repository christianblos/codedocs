<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Splits a string.
 */
class Split extends MarkupFunction
{
    const FUNC_NAME = 'split';

    /**
     * @param string $text  The text to split
     * @param string $using The delimeter. Default is \n
     *
     * @return string[]
     */
    public function __invoke($text, $using = PHP_EOL)
    {
        return explode($using, $text);
    }
}
