<?php
namespace CodeDocs\Doc;

use CodeDocs\Doc\Parser\ArrayContext;
use CodeDocs\Doc\Parser\FuncContext;
use CodeDocs\Exception\MarkupException;
use CodeDocs\State;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

class Invoker
{
    /**
     * @var callable[]
     */
    private $functions = [];

    /**
     * @param string   $name
     * @param callable $function
     */
    public function define($name, callable $function)
    {
        $this->functions[$name] = $function;
    }

    /**
     * @param FuncContext $func
     * @param State       $state
     *
     * @return mixed
     * @throws MarkupException
     */
    public function call(FuncContext $func, State $state)
    {
        if (!isset($this->functions[$func->name])) {
            throw new MarkupException(sprintf('unknown function %s()', $func->name));
        }

        $callable = $this->functions[$func->name];
        if (!is_callable($callable)) {
            throw new MarkupException(sprintf('function %s() is not callable', $func->name));
        }

        $params = $this->resolveParams($callable, $func, $state);

        if ($callable instanceof MarkupFunction) {
            $callable->setState($state);
        }

        return call_user_func_array($callable, $params);
    }

    /**
     * @param callable    $callable
     * @param FuncContext $func
     * @param State       $state
     *
     * @return array
     * @throws MarkupException
     */
    private function resolveParams(callable $callable, FuncContext $func, State $state)
    {
        $refParams = $this->getRefParamsFromCallable($callable, $func->name);
        $params    = [];
        $types     = [];

        foreach ($refParams as $idx => $refParam) {
            $types[] = $this->getType($refParam);

            if (isset($func->params[$refParam->name])) {
                $params[] = $func->params[$refParam->name];
            } elseif ($refParam->isDefaultValueAvailable()) {
                $params[] = $refParam->getDefaultValue();
            } else {
                throw new MarkupException(sprintf('missing parameter %d in function %s()', 1 + $idx, $func->name));
            }
        }

        foreach ($params as $key => $param) {
            $value = $this->resolveValue($param, $state);
            $type  = gettype($value);

            if ($types[$key] && $type !== $types[$key]) {
                throw new MarkupException(sprintf(
                    'parameter %d of function %s() must be of type %s. But %s was given',
                    $key + 1,
                    $func->name,
                    $types[$key],
                    $type
                ));
            }

            $params[$key] = $value;
        }

        return $params;
    }

    /**
     * @param callable $callable
     * @param string   $funcName
     *
     * @return ReflectionParameter[]
     * @throws MarkupException
     */
    private function getRefParamsFromCallable(callable $callable, $funcName)
    {
        if (is_string($callable)) {
            $ref       = new ReflectionFunction($callable);
            $refParams = $ref->getParameters();
        } elseif (is_array($callable)) {
            $ref       = new ReflectionMethod($callable[0], $callable[1]);
            $refParams = $ref->getParameters();
        } elseif (is_object($callable)) {
            $ref       = new ReflectionMethod($callable, '__invoke');
            $refParams = $ref->getParameters();
        } else {
            throw new MarkupException(sprintf('type of callable function %s() is not supported', $funcName));
        }

        return $refParams;
    }

    /**
     * @param mixed $value
     * @param State $state
     *
     * @return mixed
     * @throws MarkupException
     */
    private function resolveValue($value, State $state)
    {
        if ($value instanceof FuncContext) {
            return $this->call($value, $state);
        }

        if ($value instanceof ArrayContext) {
            $array = [];

            foreach ($value->values as $key => $val) {
                $array[$key] = $this->resolveValue($val, $state);
            }

            return $array;
        }

        return $value;
    }

    /**
     * @param ReflectionParameter $param
     *
     * @return string
     */
    private function getType(ReflectionParameter $param)
    {
        if (version_compare(PHP_VERSION, '7.0', '>=')) {
            return (string) $param->getType();
        }

        // fallback for PHP < 7.0
        // try to match type hint from stringified parameter, e.g. "Parameter #0 [ <required> int $foo ]"
        if (preg_match('/\<\w+?\> (?<type>\w+)/', (string) $param, $matches)) {
            return $matches['type'];
        }

        return '';
    }
}
