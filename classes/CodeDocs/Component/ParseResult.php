<?php
namespace CodeDocs\Component;

use CodeDocs\Annotation\AnnotationList;

class ParseResult
{
    /**
     * @var AnnotationList
     */
    private $annotations;

    /**
     * @var \string[]
     */
    private $classes;

    /**
     * @param AnnotationList $annotations
     * @param string[]       $classes
     */
    public function __construct(AnnotationList $annotations, $classes)
    {
        $this->annotations = $annotations;
        $this->classes = $classes;
    }

    /**
     * @return AnnotationList
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return string[]
     */
    public function getClasses()
    {
        return $this->classes;
    }
}
