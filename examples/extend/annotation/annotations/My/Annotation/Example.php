<?php
namespace My\Annotation;

use CodeDocs\Annotation\Annotation;

/**
 * @Annotation
 */
class Example extends Annotation
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $something;
}
