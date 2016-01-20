<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\Config;
use CodeDocs\Component\ParseResult;

abstract class Processor
{
    /**
     * @var array
     */
    private $params;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getParam($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     */
    abstract public function run(ParseResult $parseResult, Config $config);
}
