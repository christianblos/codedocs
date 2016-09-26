<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * @CodeDocs\Topic(file="functions/file.md")
 *
 * Returns the path either of the current file or of a class.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\File::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ file() }}
 * \{{ file(of:'SomeClass') }}
 * ```
 */
class File extends MarkupFunction
{
    const FUNC_NAME = 'file';

    /**
     * @param string|null $of The class name
     *
     * @return string
     */
    public function __invoke($of = null)
    {
        if ($of === null) {
            return $this->state->currentFile;
        }

        $refClass = $this->state->getClass($of);

        if ($refClass === null) {
            return null;
        }

        return $refClass->fileName;
    }
}
