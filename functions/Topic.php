<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;
use CodeDocs\Exception\MarkupException;
use CodeDocs\Topic as TopicAnnot;

/**
 * Returns a topic created by the Topic-Annotation.
 */
class Topic extends MarkupFunction
{
    const FUNC_NAME = 'topic';

    /**
     * @param string $id The topic id
     *
     * @return string
     * @throws MarkupException
     */
    public function __invoke($id)
    {
        foreach ($this->state->annotations as $annotation) {
            if ($annotation instanceof TopicAnnot && $annotation->id === $id) {
                return $annotation->content;
            }
        }

        throw new MarkupException(sprintf('topic with id "%s" not found', $id));
    }
}
