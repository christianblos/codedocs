<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Returns a param from the configuration.
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
