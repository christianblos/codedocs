<?php
namespace MyNamespace;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;
use CodeDocs\Markup\Markup;

/**
 * @Annotation
 */
class CurrentDate extends Markup
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param AnnotationList $annotationList
     * @param Config         $config
     *
     * @return string
     */
    public function buildContent(AnnotationList $annotationList, Config $config)
    {
        return date($this->value);
    }
}
