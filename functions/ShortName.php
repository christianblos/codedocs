<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Returns the class name without namespace.
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
