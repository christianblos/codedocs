<?php
namespace CodeDocs\SourceCode\Ref;

class RefClass
{
    /**
     * @var string
     */
    public $fileName;

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
     * string[]
     */
    public $implements = [];

    /**
     * @var string
     */
    public $extends;

    /**
     * string[]
     */
    public $traits = [];

    /**
     * @var bool
     */
    public $isAbstract = false;

    /**
     * @var bool
     */
    public $isAnonymous = false;

    /**
     * @var bool
     */
    public $isFinal = false;

    /**
     * @var bool
     */
    public $isInterface = false;

    /**
     * @var bool
     */
    public $isTrait = false;

    /**
     * @var RefProperty[]
     */
    public $properties = [];

    /**
     * @var RefConstant[]
     */
    public $constants = [];

    /**
     * @var RefMethod[]
     */
    public $methods = [];
}
