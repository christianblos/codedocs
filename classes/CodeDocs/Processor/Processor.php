<?php
namespace CodeDocs\Processor;

use CodeDocs\Component\ConfigurableComponent;
use CodeDocs\Model\Config;
use CodeDocs\Model\ParseResult;
use CodeDocs\Model\Source;

abstract class Processor extends ConfigurableComponent
{
    const TYPE_PRE  = 'pre';
    const TYPE_POST = 'post';

    /**
     * @param ParseResult $parseResult
     * @param Config      $config
     * @param Source      $source
     */
    abstract public function run(ParseResult $parseResult, Config $config, Source $source);
}
