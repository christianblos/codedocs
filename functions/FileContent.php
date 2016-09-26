<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;

/**
 * @CodeDocs\Topic(file="functions/fileContent.md")
 *
 * Returns the content of a file.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\FileContent::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ fileContent(of:'path/to/file') }}
 * ```
 */
class FileContent extends MarkupFunction
{
    const FUNC_NAME = 'fileContent';

    /**
     * @param string $of The path to the file relative to the baseDir
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($of)
    {
        $of = $this->state->config->getBaseDir() . '/' . $of;

        if (!file_exists($of)) {
            throw new MarkupException(sprintf('file %s does not exist', $of));
        }

        return trim(file_get_contents($of));
    }
}
