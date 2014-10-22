<?php
namespace CodeDocs;

/**
 *
 */
class TemplateData
{

    /**
     * @var TreeItem[]
     */
    public $items = [];

    /**
     * @var TreeItem
     */
    public $currentItem;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var array
     */
    public $additional = [];
}