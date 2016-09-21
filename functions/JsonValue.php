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
 * | Name | Type   | Description
 * | ---- | ------ | ------------
 * | of   | string | The json file relative to the baseDir
 * | key  | string | The key
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
     * @param string $of
     * @param string $key
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
