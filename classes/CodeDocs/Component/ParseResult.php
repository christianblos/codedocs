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
     * @var ClassList
     */
    private $classes;

    /**
     * @param AnnotationList $annotations
     * @param ClassList      $classes
     */
    public function __construct(AnnotationList $annotations, ClassList $classes)
    {
        $this->annotations = $annotations;
        $this->classes     = $classes;
    }

    /**
     * @return AnnotationList
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }

    /**
     * @return ClassList
     */
    public function getClasses()
    {
        return $this->classes;
    }
}
