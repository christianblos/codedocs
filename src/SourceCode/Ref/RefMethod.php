<?php
namespace CodeDocs\SourceCode\Ref;

class RefMethod
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
     * @var RefComment|null
     */
    public $docComment;

    /**
     * @var int
     */
    public $startLine;

    /**
     * @var int
     */
    public $endLine;

    /**
     * @var string
     */
    public $visibility;

    /**
     * @var bool
     */
    public $isStatic = false;

    /**
     * @var bool
     */
    public $isAbstract = false;

    /**
     * @var bool
     */
    public $isFinal = false;

    /**
     * @var string
     */
    public $returnType;

    /**
     * @var RefParam[]
     */
    public $params = [];
}
