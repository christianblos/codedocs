<?php
namespace CodeDocs\Collection;

use CodeDocs\Processor\Processor;

class ProcessorList
{
    /**
     * @var Processor[]
     */
    private $processors = [];

    /**
     * @param Processor $processor
     */
    public function add(Processor $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @return Processor[]
     */
    public function toArray()
    {
        return $this->processors;
    }
}
