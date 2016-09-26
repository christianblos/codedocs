<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\SourceCode\Ref\RefClass;

/**
 * @CodeDocs\Topic(file="functions/classes.md")
 *
 * Returns a list of classes matching the given criteria.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\Classes::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ classes(extends:'\CodeDocs\Doc\MarkupFunction') }}
 * ```
 */
class Classes extends MarkupFunction
{
    const FUNC_NAME = 'classes';

    /**
     * @param string        $matches    Regex to match class name
     * @param string        $extends    Returns only classes extending this class
     * @param string[]      $implements Returns only classes implementing these interfaces
     * @param string[]|null $list       Returns only classes in this list
     *
     * @return string[]
     */
    public function __invoke($matches = null, $extends = null, array $implements = [], $list = null)
    {
        $classes = [];

        foreach ($this->state->classes as $class) {
            if ($list !== null && !in_array($class->name, $list, true)) {
                continue;
            }

            if ($this->filterMatches($class, $matches)
                && $this->filterExtends($class, $extends)
                && $this->filterImplements($class, $implements)
            ) {
                $classes[] = $class->name;
            }
        }

        return $classes;
    }

    /**
     * @param RefClass $class
     * @param string   $matches
     *
     * @return bool
     */
    protected function filterMatches(RefClass $class, $matches)
    {
        if (!$matches) {
            return true;
        }

        return preg_match($matches, $class->name) > 0;
    }

    /**
     * @param RefClass $class
     * @param string   $extends
     *
     * @return bool
     */
    protected function filterExtends(RefClass $class, $extends)
    {
        if (!$extends) {
            return true;
        }

        return $class->extends === ltrim($extends, '\\');
    }

    /**
     * @param RefClass $class
     * @param string[] $interfaces
     *
     * @return bool
     */
    protected function filterImplements(RefClass $class, array $interfaces)
    {
        if (empty($interfaces)) {
            return true;
        }

        foreach ($interfaces as $interface) {
            $interface = ltrim($interface, '\\');

            if (!in_array($interface, $class->implements, true)) {
                return false;
            }
        }

        return true;
    }
}
