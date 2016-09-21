<?php
namespace CodeDocs;

/**
 * This interface ensures that the text content below the doc comment annotaion
 * will be added to the Annotation class.
 */
interface ContentInterface
{

    /**
     * @param string $content
     */
    public function setContent($content);
}
