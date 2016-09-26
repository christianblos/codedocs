<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Topic as TopicAnnot;
use CodeDocs\Type\Parsable;

/**
 * @CodeDocs\Topic(file="functions/topic.md")
 *
 * Returns a topic created by the Topic-Annotation.
 *
 * #### Parameters
 *
 * {{ methodParamsTable(of: '\CodeDocs\Func\Topic::__invoke') }}
 *
 * #### Example
 *
 * ```
 * \{{ topic(id:'myTopic') }}
 * ```
 */
class Topic extends MarkupFunction
{
    const FUNC_NAME = 'topic';

    /**
     * @param string $id The topic id
     *
     * @return string|Parsable
     * @throws MarkupException
     */
    public function __invoke($id)
    {
        foreach ($this->state->annotations as $annotation) {
            if ($annotation instanceof TopicAnnot && $annotation->id === $id) {
                return new Parsable($annotation->content);
            }
        }

        throw new MarkupException(sprintf('topic with id "%s" not found', $id));
    }
}
