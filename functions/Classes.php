<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\SourceCode\Ref\RefClass;

/**
 * Returns a list of classes matching the given criteria.
 *
 * @CodeDocs\Topic(file="functions/classes.md")
 *
 * # {{ defaultValue(of: '\CodeDocs\Func\Classes::FUNC_NAME') }}()
 *
 * {{ docComment(of: '\CodeDocs\Func\Classes', firstLine: true, excludeAnnotations: true) }}
 *
 * ### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\Classes::__invoke') }}
 *
 * ### Example
 *
 * Documentation source:
 *
 * ```
 * {{ fileContent(of: 'examples/functions/classes/docs-src/doc.md') }}
 * ```
 *
 * Result:
 *
 * ```
 * {{ fileContent(of: 'examples/functions/classes/docs/doc.md') }}
 * ```
 *
 * [See full example code here](../../examples/functions/classes)
 */
class Classes extends MarkupFunction
{
    const FUNC_NAME = 'classes';

    /**
     * @param string|null   $matches    Regex to match class name
     * @param string|null   $extends    Returns only classes extending this class
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
