<?php
namespace CodeDocs\SourceCode\Ref;

class RefProperty
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
    public $default;

    /**
     * @var string
     */
    public $visibility;

    /**
     * @var bool
     */
    public $isStatic = false;

    /**
     * @var int
     */
    public $line;

    /**
     * @var RefComment|null
     */
    public $docComment;

    /**
     * @var string|null
     */
    public $type;
}
