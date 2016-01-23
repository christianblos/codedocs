<?php
namespace CodeDocs\Processor;

use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;

abstract class Processor
{
    const TYPE_PRE  = 'pre';
    const TYPE_POST = 'post';

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
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     */
    abstract public function run(ParseResult $parseResult, Config $config, Source $source);
}
