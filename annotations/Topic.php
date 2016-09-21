<?php
namespace CodeDocs;

/**
 * Creates a topic that can be used by the topic() markup function.
 *
 * @Annotation
 */
class Topic extends Annotation implements ContentInterface
{
    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $content;

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
