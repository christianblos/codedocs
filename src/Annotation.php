<?php
namespace CodeDocs;

use Doctrine\Common\Annotations\Annotation as DoctrineAnnotation;

/**
 * @Annotation
 */
class Annotation extends DoctrineAnnotation
{
    /**
     * @var string
     */
    public $originClass;

    /**
     * @var string|null
     */
    public $originMethod;

    /**
     * @var string|null
     */
    public $originProperty;

    /**
     * @var string|null
     */
    public $originConstant;
}
