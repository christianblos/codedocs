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
 * {{ methodParamsTable(of: '\CodeDocs\Func\Param::__invoke') }}
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
     * @param string $of      The name of the param
     * @param mixed  $default The default value if param is not set
     *
     * @return string
     */
    public function __invoke($of, $default = null)
    {
        return $this->state->config->getParam($of, $default);
    }
}
