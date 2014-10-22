<?php
namespace CodeDocs;

/**
 *
 */
class TreeItem
{
    /**
     * @var string
     */
    public $src;

    /**
     * @var string
     */
    public $dest;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $relUrl;

    /**
     * @var TreeItem[]
     */
    public $children = [];

    /**
     * @var TreeItem
     */
    public $parent;
}