<?php
namespace CodeDocs\Collection;

class ClassList
{
    /**
     * @var string[]
     */
    private $classes = [];

    /**
     * @param string[] $classes
     */
    public function __construct(array $classes = [])
    {
        $this->classes = $classes;
    }

    /**
     * @param callable $callback
     *
     * @return ClassList
     */
    public function filter(callable $callback = null)
    {
        return new self(array_filter($this->classes, $callback));
    }

    /**
     * @param callable $callback
     *
     * @return mixed[]
     */
    public function map(callable $callback)
    {
        return array_map($callback, $this->classes);
    }

    /**
     * @return string[]
     */
    public function toArray()
    {
        return $this->classes;
    }

    /**
     * @return string|null
     */
    public function getFirst()
    {
        return reset($this->classes);
    }
}
