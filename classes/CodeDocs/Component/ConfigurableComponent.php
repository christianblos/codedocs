<?php
namespace CodeDocs\Component;

use CodeDocs\Exception\ConfigException;

abstract class ConfigurableComponent
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
     * @param $name
     *
     * @return mixed
     */
    protected function getMandatoryParam($name)
    {
        $value = $this->getParam($name);

        if ($value === null) {
            throw new ConfigException(sprintf('no %s param given for %s', $name, __CLASS__));
        }

        return $value;
    }
}
