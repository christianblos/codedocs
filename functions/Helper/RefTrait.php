<?php
namespace CodeDocs\Func\Helper;

use CodeDocs\Exception\MarkupException;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\SourceCode\Ref\RefMethod;
use CodeDocs\SourceCode\Ref\RefProperty;
use CodeDocs\State;

trait RefTrait
{
    /**
     * @param State  $state
     * @param string $source
     *
     * @return RefClass|RefMethod|RefProperty
     * @throws MarkupException
     */
    protected function getRef(State $state, $source)
    {
        $pos = strpos($source, '::');

        if ($pos) {
            $class  = substr($source, 0, $pos);
            $member = substr($source, $pos + 2);

            if (strpos($member, '$') === 0) {
                return $this->getProperty($state, $class, substr($member, 1));
            }

            return $this->getMethod($state, $class, $member);
        }

        return $this->getClass($state, $source);
    }

    /**
     * @param State  $state
     * @param string $class
     *
     * @return RefClass
     * @throws MarkupException
     */
    private function getClass(State $state, $class)
    {
        $refClass = $state->getClass($class);
        if ($refClass === null) {
            throw new MarkupException(sprintf('class %s does not exist', $class));
        }

        return $refClass;
    }

    /**
     * @param State  $state
     * @param string $class
     * @param string $method
     *
     * @return RefMethod
     * @throws MarkupException
     */
    private function getMethod(State $state, $class, $method)
    {
        $refClass = $this->getClass($state, $class);

        if (!isset($refClass->methods[$method])) {
            throw new MarkupException(sprintf('method %s::%s does not exist', $class, $method));
        }

        return $refClass->methods[$method];
    }

    /**
     * @param State  $state
     * @param string $class
     * @param string $property
     *
     * @return RefProperty
     * @throws MarkupException
     */
    private function getProperty(State $state, $class, $property)
    {
        $refClass = $this->getClass($state, $class);

        if (!isset($refClass->properties[$property])) {
            throw new MarkupException(sprintf('property %s::$%s does not exist', $class, $property));
        }

        return $refClass->properties[$property];
    }
}
