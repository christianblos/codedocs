<?php
namespace CodeDocs;

use CodeDocs\SourceCode\Ref\RefClass;

class State
{
    /**
     * @var RunnerConfig
     */
    public $config;

    /**
     * @var RefClass[]
     */
    public $classes = [];

    /**
     * @var Annotation[]
     */
    public $annotations = [];

    /**
     * @var string
     */
    public $currentFile;

    /**
     * @param string $class
     *
     * @return RefClass|null
     */
    public function getClass($class)
    {
        $class = ltrim($class, '\\');

        if (!isset($this->classes[$class])) {
            return null;
        }

        return $this->classes[$class];
    }
}
