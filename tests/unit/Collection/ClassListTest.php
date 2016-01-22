<?php
namespace CodeDocs\Test\Collection;

use CodeDocs\Collection\ClassList;

/**
 * @covers \CodeDocs\Collection\ClassList
 */
class ClassListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassList
     */
    private $list;

    /**
     *
     */
    protected function setUp()
    {
        $this->list = new ClassList(['FirstClass', 'SecondClass']);
    }

    /**
     * @test
     */
    public function can_be_converted_to_array()
    {
        $classes = $this->list->toArray();
        $this->assertCount(2, $classes);
        $this->assertEquals('FirstClass', $classes[0]);
    }

    /**
     * @test
     */
    public function can_be_filtered()
    {
        $list = $this->list->filter(function ($class) {
            return $class === 'SecondClass';
        });

        $classes = $list->toArray();
        $this->assertCount(1, $classes);
        $this->assertSame('SecondClass', reset($classes));
    }

    /**
     * @test
     */
    public function can_be_mapped()
    {
        $list = $this->list->map(function ($class) {
            return '#' . $class;
        });

        $this->assertEquals('#FirstClass', $list[0]);
    }

    /**
     * @test
     */
    public function can_return_first_class()
    {
        $this->assertSame('FirstClass', $this->list->getFirst());
    }
}
