<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;

/**
 * @CodeDocs\Topic(file="functions/jsonValue.md")
 *
 * Returns a value from a json file.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\JsonValue::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ jsonValue(of:'composer.json', key:'name') }}
 * ```
 */
class JsonValue extends MarkupFunction
{
    const FUNC_NAME = 'jsonValue';

    /**
     * @param string $of  The json file relative to the baseDir
     * @param string $key The key
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($of, $key)
    {
        $of = $this->state->config->getBaseDir() . '/' . $of;

        if (!file_exists($of)) {
            throw new MarkupException(sprintf('json file %s does not exist', $of));
        }

        $content = file_get_contents($of);
        $data    = json_decode($content, true);

        if ($data === null) {
            throw new MarkupException(sprintf('invalid json file %s', $of));
        }

        if (!isset($data[$key])) {
            return '';
        }

        return (string)$data[$key];
    }
}
