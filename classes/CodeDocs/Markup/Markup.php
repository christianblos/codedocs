<?php
namespace CodeDocs\Markup;

use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;
use CodeDocs\ValueObject\Parsable;
use Doctrine\Common\Annotations\Annotation;

/**
 *
 */
abstract class Markup extends Annotation
{
    /**
     * @var string
     */
    private $foundMarkupString;

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     *
     * @return Parsable|string
     */
    abstract public function buildContent(ParseResult $parseResult, Config $config, Source $source);

    /**
     * @param string $markupString
     */
    public function setMarkupString($markupString)
    {
        $this->foundMarkupString = $markupString;
    }

    /**
     * @return string
     */
    public function getMarkupString()
    {
        return $this->foundMarkupString;
    }
}
