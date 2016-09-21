<?php
namespace CodeDocs\SourceCode\Ref;

class RefNamespace
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string[] alias => name
     */
    public $uses = [];
}
