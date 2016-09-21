<?php
namespace CodeDocs\SourceCode\Ref;

class RefConstant
{
    /**
     * @var RefClass
     */
    public $class;

    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var int
     */
    public $line;

    /**
     * @var RefComment|null
     */
    public $docComment;
}
