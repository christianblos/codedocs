<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Replaces strings in the given text.
 *
 * @CodeDocs\Topic(file="functions/replace.md")
 *
 * # {{ defaultValue(of: '\CodeDocs\Func\Replace::FUNC_NAME') }}()
 *
 * {{ docComment(of: '\CodeDocs\Func\Replace', firstLine: true, excludeAnnotations: true) }}
 *
 * ### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\Replace::__invoke') }}
 *
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
