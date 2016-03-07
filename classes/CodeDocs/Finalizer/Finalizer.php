<?php
namespace CodeDocs\Finalizer;

use CodeDocs\Component\ConfigurableComponent;
use CodeDocs\Model\Config;

abstract class Finalizer extends ConfigurableComponent
{
    /**
     * @param Config $config
     */
    abstract public function run(Config $config);
}
