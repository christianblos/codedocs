<?php
namespace CodeDocs\Annotation;

use CodeDocs\Exception\AnnotationException;
use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 *
 */
class Annotation extends DoctrineAnnotation
{
    /**
     * @var string
     */
    public $originClass;

    /**
     * @var string
     */
    public $originMethod;

    /**
     * @var string
     */
    public $originProperty;

    /**
     * @return ReflectionClass|ReflectionMethod|ReflectionProperty
     */
    public function getReflector()
    {
        if (!$this->originClass) {
            throw new AnnotationException('no originClass set');
        }

        if ($this->originMethod) {
            return new ReflectionMethod($this->originClass, $this->originMethod);
        }

        if ($this->originProperty) {
            return new ReflectionProperty($this->originClass, $this->originProperty);
        }

        return new ReflectionClass($this->originClass);
    }
}
