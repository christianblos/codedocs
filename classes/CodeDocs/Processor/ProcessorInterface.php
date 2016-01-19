<?php
namespace CodeDocs\Processor;

use CodeDocs\Annotation\AnnotationList;
use CodeDocs\Component\Config;

interface ProcessorInterface
{
    /**
     * @param AnnotationList $annotationList
     * @param Config         $config
     */
    public function run(AnnotationList $annotationList, Config $config);
}
