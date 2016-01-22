<?php
namespace CodeDocs\Test\Annotation;

use CodeDocs\Annotation\Annotation;

/**
 * @covers CodeDocs\Annotation\Annotation
 */
class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function can_return_class_reflector()
    {
        $annotation              = new Annotation([]);
        $annotation->originClass = SomeClass::class;

        $ref = $annotation->getReflector();
        $this->assertInstanceOf(\ReflectionClass::class, $ref);
        $this->assertEquals(SomeClass::class, $ref->name);
    }

    /**
     * @test
     */
    public function can_return_method_reflector()
    {
        $annotation               = new Annotation([]);
        $annotation->originClass  = SomeClass::class;
        $annotation->originMethod = 'getValue';

        $ref = $annotation->getReflector();
        $this->assertInstanceOf(\ReflectionMethod::class, $ref);
        $this->assertEquals(SomeClass::class, $ref->class);
        $this->assertEquals('getValue', $ref->name);
    }

    /**
     * @test
     */
    public function can_return_property_reflector()
    {
        $annotation                 = new Annotation([]);
        $annotation->originClass    = SomeClass::class;
        $annotation->originProperty = 'value';

        $ref = $annotation->getReflector();
        $this->assertInstanceOf(\ReflectionProperty::class, $ref);
        $this->assertEquals(SomeClass::class, $ref->class);
        $this->assertEquals('value', $ref->name);
    }

    /**
     * @test
     * @expectedException \CodeDocs\Exception\AnnotationException
     */
    public function throws_exception_if_class_is_not_set()
    {
        $annotation = new Annotation([]);

        $annotation->getReflector();
    }
}

class SomeClass
{
    private $value = 1;

    public function getValue()
    {
        return $this->value;
    }
}
