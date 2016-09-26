<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Tag;

/**
 * @CodeDocs\Topic(file="functions/notTagged.md")
 *
 * Returns a list of classes that are not tagged by the Tag-Annotation.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\NotTagged::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ notTagged(by:'someTag') }}
 * ```
 */
class NotTagged extends MarkupFunction
{
    const FUNC_NAME = 'notTagged';

    /**
     * @param string   $by      The tag name
     * @param string[] $classes Use this list of classes instead of all parsed ones
     *
     * @return string[]
     */
    public function __invoke($by, $classes = null)
    {
        $items         = [];
        $taggedClasses = [];

        foreach ($this->state->annotations as $annotation) {
            if (!$annotation instanceof Tag) {
                continue;
            }

            if ($annotation->value === $by) {
                $taggedClasses[] = $annotation->originClass;
            }
        }

        foreach ($this->state->classes as $refClass) {
            if ($classes !== null && !in_array($refClass->name, $classes, true)) {
                continue;
            }

            if (in_array($refClass->name, $taggedClasses, true)) {
                continue;
            }

            $items[] = $refClass->name;
        }

        return $items;
    }
}
