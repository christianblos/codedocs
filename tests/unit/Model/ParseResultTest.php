<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\Collection\AnnotationList;
use CodeDocs\Collection\ClassList;
use CodeDocs\Model\ParseResult;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @covers \CodeDocs\Model\ParseResult
 */
class ParseResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ParseResult
     */
    private $result;

    /**
     * @var AnnotationList|MockObject
     */
    private $annotations;

    /**
     * @var ClassList|MockObject
     */
    private $classes;

    /**
     *
     */
    protected function setUp()
    {
        $this->annotations = $this->getMockBuilder(AnnotationList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->classes = $this->getMockBuilder(ClassList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->result = new ParseResult($this->annotations, $this->classes);
    }

    /**
     * @test
     */
    public function contains_annotations()
    {
        $this->assertSame($this->annotations, $this->result->getAnnotations());
    }

    /**
     * @test
     */
    public function contains_classes()
    {
        $this->assertSame($this->classes, $this->result->getClasses());
    }
}
