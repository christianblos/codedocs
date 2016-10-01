<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\Tag;

/**
 * Returns a list of classes matching the given criteria.
 */
class Classes extends MarkupFunction
{
    const FUNC_NAME = 'classes';

    /**
     * @param string|null          $matches       Regex to match class name
     * @param string|null          $extends       Returns only classes extending this class
     * @param string|string[]|null $implements    Returns only classes implementing these interfaces
     * @param string|string[]|null $taggedWith    Returns only classes with these Tag annotations
     * @param string|string[]|null $notTaggedWith Returns only classes without these Tag annotations
     *
     * @return string[]
     */
    public function __invoke(
        $matches = null,
        $extends = null,
        $implements = null,
        $taggedWith = null,
        $notTaggedWith = null
    ) {
        $classes       = [];
        $filterClasses = [];

        if ($taggedWith) {
            $filterClasses = $this->getClassesTaggedWith((array)$taggedWith);
        }

        foreach ($this->state->classes as $class) {
            if ($filterClasses && !in_array($class->name, $filterClasses, true)) {
                continue;
            }

            if ($this->filterMatches($class, $matches)
                && $this->filterExtends($class, $extends)
                && $this->filterImplements($class, $implements === null ? [] : (array)$implements)
            ) {
                $classes[] = $class->name;
            }
        }

        if ($notTaggedWith) {
            $classes = array_diff(
                $classes,
                $this->getClassesTaggedWith((array)$notTaggedWith)
            );
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

    /**
     * @param string[] $tags
     *
     * @return string[]
     */
    protected function getClassesTaggedWith(array $tags)
    {
        $classes = [];

        foreach ($this->state->annotations as $annotation) {
            if (!$annotation instanceof Tag || !in_array($annotation->value, $tags, true)) {
                continue;
            }

            $classes[] = $annotation->originClass;
        }

        return $classes;
    }
}
