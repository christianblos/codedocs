<?php
namespace CodeDocs\Test\Collection;

use CodeDocs\Collection\ProcessorList;
use CodeDocs\Processor\Processor;

/**
 * @covers \CodeDocs\Collection\ProcessorList
 */
class ProcessorListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProcessorList
     */
    private $list;

    /**
     *
     */
    protected function setUp()
    {
        $this->list = new ProcessorList();
    }

    /**
     * @test
     */
    public function can_add_processor()
    {
        $processor = $this->getMockBuilder(Processor::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->list->add($processor);

        $processors = $this->list->toArray();
        $this->assertCount(1, $processors);
        $this->assertSame($processor, $processors[0]);
    }
}
