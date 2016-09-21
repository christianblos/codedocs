<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Tag;

/**
 * @CodeDocs\Topic(file="functions/tagged.md")
 *
 * Returns a list of classes tagged by the Tag-Annotation.
 *
 * #### Parameters
 *
 * | Name     | Type     | Description
 * | -------- | -------- | ------------
 * | by       | string   | The tag name
 * | contents | bool     | (Optional) True to return annotation contents instead of class name or label
 * | classes  | string[] | (Optional) Use this list of classes instead of all parsed ones
 *
 * #### Example
 *
 * ```
 * \{{ tagged(by:'someTag') }}
 * ```
 */
class Tagged extends MarkupFunction
{
    const FUNC_NAME = 'tagged';

    /**
     * @param string   $by
     * @param bool     $contents
     * @param string[] $classes
     *
     * @return string[]
     */
    public function __invoke($by, $contents = false, $classes = null)
    {
        $items = [];

        foreach ($this->state->annotations as $annotation) {
            if (!$annotation instanceof Tag || $annotation->value !== $by) {
                continue;
            }

            if ($classes !== null && !in_array($annotation->originClass, $classes, true)) {
                continue;
            }

            if ($contents === true) {
                $items[] = $annotation->content;
            } else {
                $items[] = $annotation->label ?: $annotation->originClass;
            }
        }

        return $items;
    }
}
