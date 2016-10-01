<?php
namespace CodeDocs;

/**
 * Creates a tag that can be used to filter classes, methods, etc.
 *
 * @Annotation
 */
class Tag extends Annotation
{
    /**
     * The tag name
     *
     * @var string
     */
    public $value;
}
