<?php
namespace CodeDocs\Component;

use CodeDocs\Model\Config;

abstract class Plugin
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
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    /**
     * @param Config $config
     */
    abstract public function mount(Config $config);
}
