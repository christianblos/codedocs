<?php
namespace CodeDocs\Component;

use CodeDocs\ValueObject\Directory;

class Source
{
    /**
     * @var Directory
     */
    public $baseDir;

    /**
     * @var Directory
     */
    public $docsDir;

    /**
     * @var Directory[]
     */
    public $classDirs = [];

}
