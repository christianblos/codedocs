<?php
namespace CodeDocs\Processor;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;

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
     * @param AnnotationList $annotationList
     * @param Config         $config
     */
    abstract public function run(AnnotationList $annotationList, Config $config);
}
