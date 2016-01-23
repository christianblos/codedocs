<?php
namespace MyNamespace;

use CodeDocs\Markup\Markup;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Parsable;

/**
 * @Annotation
 */
class CurrentDate extends Markup
{
    /**
     * @var string
     */
    public $value;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    public function buildContent(ParseResult $parseResult, Config $config, Source $source)
    {
        return date($this->value);
    }
}
