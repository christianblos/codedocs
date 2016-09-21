<?php
namespace CodeDocs\Doc;

use CodeDocs\State;

abstract class MarkupFunction
{
    /**
     * @var State
     */
    protected $state;

    /**
     * @param State $state
     */
    public function setState(State $state)
    {
        $this->state = $state;
    }
}
