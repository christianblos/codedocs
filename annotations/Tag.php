<?php
namespace CodeDocs;

/**
 * Creates a tag that can be used by the tagged() and notTagged() markup function.
 *
 * @Annotation
 */
class Tag extends Annotation implements ContentInterface
{
    /**
     * The tag name
     *
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $label;

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
