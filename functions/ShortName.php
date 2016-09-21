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
 * | Name | Type   | Description
 * | ---- | -------| ------------
 * | of   | string | The class name
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
     * @param string $of
     *
     * @return string
     */
    public function __invoke($of)
    {
        $parts = explode('\\', $of);

        return end($parts);
    }
}
