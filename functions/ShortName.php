<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * @CodeDocs\Topic(file="functions/shortName.md")
 *
 * Returns the class name without namespace.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\ShortName::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ shortName(of:'\My\Namespace\ClassName') }}
 * ```
 */
class ShortName extends MarkupFunction
{
    const FUNC_NAME = 'shortName';

    /**
     * @param string $of The class name
     *
     * @return string
     */
    public function __invoke($of)
    {
        $parts = explode('\\', $of);

        return end($parts);
    }
}
