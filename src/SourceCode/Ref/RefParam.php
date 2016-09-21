<?php
namespace CodeDocs\SourceCode\Ref;

class RefParam
{
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
    public $type;

    /**
     * @var bool
     */
    public $byRef = false;

    /**
     * @var bool
     */
    public $isVariadic = false;
}
