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
 * {{ methodParamsTable(of: '\CodeDocs\Func\Tagged::__invoke') }}
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
     * @param string   $by       The tag name
     * @param bool     $contents True to return annotation contents instead of class name or label
     * @param string[] $classes  Use this list of classes instead of all parsed ones
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
