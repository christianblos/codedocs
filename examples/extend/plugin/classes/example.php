<?php
namespace My;

use CodeDocs\Component\Plugin;
use CodeDocs\Model\Config;

class MyPlugin extends Plugin
{

    /**
     * @param Config $config
     */
    public function mount(Config $config)
    {
        $config->setParam('foo', $this->getParam('foo'));
    }
}
