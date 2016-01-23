<?php
namespace CodeDocs\Test\Collection;

use CodeDocs\Annotation\Annotation;
use CodeDocs\Collection\AnnotationList;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use ReflectionClass;

/**
 * @covers \CodeDocs\Collection\AnnotationList
 */
class AnnotationListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Annotation|MockObject
     */
    private $annotation1;

    /**
     * @var Annotation|MockObject
     */
    private $annotation2;

    /**
     * @var AnnotationList
     */
    private $list;

    /**
     *
     */
    protected function setUp()
    {
        $this->annotation1 = $this->getMockBuilder(Annotation::class)
            ->disableOriginalConstructor()
            ->setMockClassName('AnnotationOne')
            ->getMock();

        $this->annotation2 = $this->getMockBuilder(Annotation::class)
            ->disableOriginalConstructor()
            ->setMockClassName('AnnotationTwo')
            ->getMock();

        $this->list = new AnnotationList([$this->annotation1, $this->annotation2]);
    }

    /**
     * @test
     */
    public function constructor_only_excepts_annotations()
    {
        $annotation1 = $this->getMock(Annotation::class, null, [], '', false);
        $annotation2 = $this->getMock(Annotation::class, null, [], '', false);

        $list = new AnnotationList([$annotation1, new \stdClass(), $annotation2]);

        $annotations = $list->toArray();
        $this->assertCount(2, $annotations);
        $this->assertSame($annotation1, $annotations[0]);
    }

    /**
     * @test
     */
    public function can_be_filtered()
    {
        $list = $this->list->filter(function (Annotation $annotation) {
            return get_class($annotation) === 'AnnotationOne';
        });

        $annotations = $list->toArray();
        $this->assertCount(1, $annotations);
        $this->assertSame($this->annotation1, $annotations[0]);
    }

    /**
     * @test
     */
    public function can_be_filtered_by_class()
    {
        $list = $this->list->filterByClass('AnnotationOne');

        $annotations = $list->toArray();
        $this->assertCount(1, $annotations);
        $this->assertSame($this->annotation1, $annotations[0]);
    }

    /**
     * @test
     */
    public function can_be_filtered_by_reflector()
    {
        $ref = new ReflectionClass(get_class($this));

        $this->annotation1
            ->expects($this->once())
            ->method('getReflector')
            ->willReturn(null);

        $this->annotation2
            ->expects($this->once())
            ->method('getReflector')
            ->willReturn($ref);

        $list = $this->list->filterByReflector(new ReflectionClass(get_class($this)));

        $this->assertSame($this->annotation2, $list->getFirst());
    }

    /**
     * @test
     */
    public function can_be_mapped()
    {
        $list = $this->list->map(function (Annotation $annotation) {
            return get_class($annotation);
        });

        $this->assertEquals('AnnotationTwo', $list[1]);
    }

    /**
     * @test
     */
    public function can_return_first_annotation()
    {
        $this->assertSame($this->annotation1, $this->list->getFirst());
    }
}
