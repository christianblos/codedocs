<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * @CodeDocs\Topic(file="functions/param.md")
 *
 * Returns a param from the configuration.
 *
 * #### Parameters
 *
 * | Name    | Type   | Description
 * | ------- | ------ | ------------
 * | of      | string | The name of the param
 * | default | mixed  | (Optional) The default value if param is not set
 *
 * #### Example
 *
 * ```
 * \{{ param(of:'appName') }}
 * \{{ param(of:'env', default:'development') }}
 * ```
 */
class Param extends MarkupFunction
{
    const FUNC_NAME = 'param';

    /**
     * @param string $of
     * @param mixed  $default
     *
     * @return string
     */
    public function __invoke($of, $default = null)
    {
        return $this->state->config->getParam($of, $default);
    }
}
