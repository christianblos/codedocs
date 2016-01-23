<?php
namespace CodeDocs\Collection;

use CodeDocs\Annotation\Annotation;
use Reflector;

/**
 * Collection of Annotation classes
 */
class AnnotationList
{
    /**
     * @var Annotation[]
     */
    private $annotations = [];

    /**
     * @param Annotation[] $annotations
     */
    public function __construct(array $annotations = [])
    {
        $this->addMulti($annotations);
    }

    /**
     * @param Annotation[] $annotations
     */
    public function addMulti(array $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotation) {
                $this->annotations[] = $annotation;
            }
        }
    }

    /**
     * @param callable $callback
     *
     * @return AnnotationList
     */
    public function filter(callable $callback = null)
    {
        return new self(array_filter($this->annotations, $callback));
    }

    /**
     * @param $className
     *
     * @return AnnotationList
     */
    public function filterByClass($className)
    {
        return $this->filter(function ($annotation) use ($className) {
            return is_a($annotation, $className);
        });
    }

    /**
     * @param Reflector $reflector
     *
     * @return AnnotationList
     */
    public function filterByReflector(Reflector $reflector)
    {
        return $this->filter(function (Annotation $annotation) use ($reflector) {
            return $annotation->getReflector() == $reflector;
        });
    }

    /**
     * @param callable $callback
     *
     * @return mixed[]
     */
    public function map(callable $callback)
    {
        return array_map($callback, $this->annotations);
    }

    /**
     * @return Annotation[]
     */
    public function toArray()
    {
        return $this->annotations;
    }

    /**
     * @return Annotation|null
     */
    public function getFirst()
    {
        return reset($this->annotations);
    }
}
