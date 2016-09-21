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
 * | Name       | Type     | Description
 * | ---------- | -------- | ------------
 * | matches    | string   | (Optional) Regex to match class name
 * | extends    | string   | (Optional) Returns only classes extending this class
 * | implements | string[] | (Optional) Returns only classes implementing these interfaces
 * | list       | string[] | (Optional) Returns only classes in this list
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
     * @param string   $matches
     * @param string   $extends
     * @param string   $implements
     * @param string[] $list
     *
     * @return string[]
     */
    public function __invoke($matches = null, $extends = null, $implements = null, $list = null)
    {
        $interfaces = [];
        $classes    = [];

        if ($implements !== null) {
            $interfaces = array_map('trim', explode(',', $implements));
        }

        foreach ($this->state->classes as $class) {
            if ($list !== null && !in_array($class->name, $list, true)) {
                continue;
            }

            if ($this->filterMatches($class, $matches)
                && $this->filterExtends($class, $extends)
                && $this->filterImplements($class, $interfaces)
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
        if (!$interfaces) {
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
