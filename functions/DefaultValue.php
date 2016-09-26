<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\SourceCode\Ref\RefClass;

/**
 * @CodeDocs\Topic(file="functions/defaultValue.md")
 *
 * Returns the default value of a class member or method param.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\DefaultValue::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ defaultValue(of:'SomeClass::SOME_CONST') }}
 * \{{ defaultValue(of:'SomeClass::$someProperty') }}
 * \{{ defaultValue(of:'SomeClass::someMethod(someParam)') }}
 * ```
 */
class DefaultValue extends MarkupFunction
{
    const FUNC_NAME = 'defaultValue';

    /**
     * @param string $of The reference to a class member or method param
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($of)
    {
        $pos = strpos($of, '::');
        if (!$pos) {
            throw new MarkupException('please use value like class::member');
        }

        $class  = substr($of, 0, $pos);
        $member = substr($of, $pos + 2);

        $refClass = $this->state->getClass($class);
        if ($refClass === null) {
            throw new MarkupException(sprintf('class %s does not exist', $class));
        }

        if (strpos($member, '$') === 0) {
            $value = $this->getPropertyValue($refClass, $member);
        } elseif (preg_match('/^(.*)\((.*)\)$/', $member, $matches)) {
            $value = $this->getParamValue($refClass, $matches[1], $matches[2]);
        } else {
            $value = $this->getConstantValue($refClass, $member);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif ($value === null) {
            return 'null';
        } elseif (is_array($value)) {
            return $value ? var_export($value, true) : '[]';
        }

        return (string)$value;
    }

    /**
     * @param RefClass $refClass
     * @param string   $member
     *
     * @return mixed
     * @throws MarkupException
     */
    private function getPropertyValue(RefClass $refClass, $member)
    {
        $member = substr($member, 1);

        if (!isset($refClass->properties[$member])) {
            throw new MarkupException(sprintf('property %s::$%s does not exist', $refClass->name, $member));
        }

        return $refClass->properties[$member]->default;
    }

    /**
     * @param RefClass $refClass
     * @param string   $member
     *
     * @return mixed
     * @throws MarkupException
     */
    private function getConstantValue(RefClass $refClass, $member)
    {
        if (!isset($refClass->constants[$member])) {
            throw new MarkupException(sprintf('constant %s::%s does not exist', $refClass->name, $member));
        }

        return $refClass->constants[$member]->value;
    }

    /**
     * @param RefClass $refClass
     * @param string   $method
     * @param string   $param
     *
     * @return mixed
     * @throws MarkupException
     */
    private function getParamValue(RefClass $refClass, $method, $param)
    {
        if (!isset($refClass->methods[$method])) {
            throw new MarkupException(sprintf('method %s::%s() does not exist', $refClass->name, $method));
        }

        $refMethod = $refClass->methods[$method];

        if (!isset($refMethod->params[$param])) {
            throw new MarkupException(sprintf(
                'param %s in method %s::%s() does not exist',
                $param,
                $refClass->name,
                $method
            ));
        }

        return $refMethod->params[$param]->default;
    }
}
