<?php
namespace CodeDocs\Test\Integration;

use CodeDocs\SourceCode\CodeParser;
use CodeDocs\SourceCode\Ref\RefClass;
use CodeDocs\SourceCode\Ref\RefComment;
use CodeDocs\SourceCode\Ref\Visibility;
use PHPUnit\Framework\TestCase;

class CodeParserTest extends TestCase
{
    public function testParser()
    {
        $parser = new CodeParser();
        $refs   = $parser->parse([__DIR__ . '/samples']);

        self::assertCount(6, $refs);

        self::assertArrayHasKey('Simple', $refs);
        self::assertArrayHasKey('CodeDocs\Integration\Base', $refs);
        self::assertArrayHasKey('CodeDocs\Integration\InterFirst', $refs);
        self::assertArrayHasKey('CodeDocs\Integration\InterSecond', $refs);
        self::assertArrayHasKey('CodeDocs\Integration\Complex', $refs);
        self::assertArrayHasKey('CodeDocs\Integration\SomeTrait', $refs);

        $this->checkSimple($refs['Simple']);

        $ref = $refs['CodeDocs\Integration\Base'];
        self::assertEquals(['CodeDocs\Integration\SomeTrait'], $ref->traits);
        self::assertTrue($ref->isAbstract);
        self::assertSame(6, $ref->startLine);
        self::assertSame(9, $ref->endLine);

        $ref = $refs['CodeDocs\Integration\InterFirst'];
        self::assertTrue($ref->isInterface);
        self::assertSame(11, $ref->startLine);
        self::assertSame(14, $ref->endLine);

        $ref = $refs['CodeDocs\Integration\InterSecond'];
        self::assertTrue($ref->isInterface);
        self::assertEquals(['CodeDocs\Integration\AnotherInterface'], $ref->implements);
        self::assertSame(16, $ref->startLine);
        self::assertSame(19, $ref->endLine);

        $ref = $refs['CodeDocs\Integration\Complex'];
        self::assertFalse($ref->isInterface);
        self::assertTrue($ref->isFinal);
        self::assertEquals(
            [
                'CodeDocs\Integration\InterFirst',
                'CodeDocs\Integration\InterSecond',
            ],
            $ref->implements
        );
        self::assertEquals('CodeDocs\Integration\Base', $ref->extends);
        self::assertSame(21, $ref->startLine);
        self::assertSame(24, $ref->endLine);

        $ref = $refs['CodeDocs\Integration\SomeTrait'];
        self::assertTrue($ref->isTrait);
        self::assertSame(26, $ref->startLine);
        self::assertSame(28, $ref->endLine);
    }

    private function checkSimple(RefClass $class)
    {
        self::assertSame(realpath(__DIR__ . '/samples/file1.php'), $class->fileName);
        self::assertSame('Simple', $class->name);

        $expectedComment            = new RefComment();
        $expectedComment->startLine = 3;
        $expectedComment->endLine   = 5;
        $expectedComment->text      = '/**
 * A simple class
 */';
        self::assertEquals($expectedComment, $class->docComment);

        self::assertSame(6, $class->startLine);
        self::assertSame(48, $class->endLine);

        self::assertNull($class->extends);
        self::assertEmpty($class->implements);
        self::assertEmpty($class->traits);
        self::assertFalse($class->isAbstract);
        self::assertFalse($class->isAnonymous);
        self::assertFalse($class->isFinal);
        self::assertFalse($class->isInterface);
        self::assertFalse($class->isTrait);

        // -------------------------------------------
        // constants
        // -------------------------------------------

        self::assertCount(2, $class->constants);

        self::assertArrayHasKey('SIMPLE_ONE', $class->constants);
        $const = $class->constants['SIMPLE_ONE'];
        self::assertSame('SIMPLE_ONE', $const->name);
        self::assertSame(1, $const->value);
        self::assertSame(8, $const->line);
        self::assertNull($const->docComment);

        self::assertArrayHasKey('SIMPLE_REF', $class->constants);
        $const = $class->constants['SIMPLE_REF'];
        self::assertSame('SIMPLE_REF', $const->name);
        self::assertNull($const->value);
        self::assertSame(13, $const->line);

        $expectedComment            = new RefComment();
        $expectedComment->startLine = 10;
        $expectedComment->endLine   = 12;
        $expectedComment->text      = '/**
     * A reference
     */';
        self::assertEquals($expectedComment, $const->docComment);

        // -------------------------------------------
        // properties
        // -------------------------------------------

        self::assertCount(8, $class->properties);

        self::assertArrayHasKey('notSet', $class->properties);
        $prop = $class->properties['notSet'];
        self::assertSame('notSet', $prop->name);
        self::assertNull($prop->default);
        self::assertFalse($prop->isStatic);
        self::assertSame(18, $prop->line);
        self::assertSame(Visibility::IS_PUBLIC, $prop->visibility);
        $expectedComment            = new RefComment();
        $expectedComment->startLine = 15;
        $expectedComment->endLine   = 17;
        $expectedComment->text      = '/**
     * @var mixed
     */';
        self::assertEquals($expectedComment, $prop->docComment);

        self::assertArrayHasKey('isNull', $class->properties);
        $prop = $class->properties['isNull'];
        self::assertSame('isNull', $prop->name);
        self::assertNull($prop->default);
        self::assertFalse($prop->isStatic);
        self::assertSame(20, $prop->line);
        self::assertSame(Visibility::IS_PRIVATE, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isTrue', $class->properties);
        $prop = $class->properties['isTrue'];
        self::assertSame('isTrue', $prop->name);
        self::assertTrue($prop->default);
        self::assertFalse($prop->isStatic);
        self::assertSame(22, $prop->line);
        self::assertSame(Visibility::IS_PROTECTED, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isFalse', $class->properties);
        $prop = $class->properties['isFalse'];
        self::assertSame('isFalse', $prop->name);
        self::assertFalse($prop->default);
        self::assertTrue($prop->isStatic);
        self::assertSame(24, $prop->line);
        self::assertSame(Visibility::IS_PUBLIC, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isInt', $class->properties);
        $prop = $class->properties['isInt'];
        self::assertSame('isInt', $prop->name);
        self::assertSame(1, $prop->default);
        self::assertTrue($prop->isStatic);
        self::assertSame(26, $prop->line);
        self::assertSame(Visibility::IS_PRIVATE, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isFloat', $class->properties);
        $prop = $class->properties['isFloat'];
        self::assertSame('isFloat', $prop->name);
        self::assertSame(123.456, $prop->default);
        self::assertTrue($prop->isStatic);
        self::assertSame(28, $prop->line);
        self::assertSame(Visibility::IS_PROTECTED, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isString', $class->properties);
        $prop = $class->properties['isString'];
        self::assertSame('isString', $prop->name);
        self::assertSame('some string', $prop->default);
        self::assertFalse($prop->isStatic);
        self::assertSame(30, $prop->line);
        self::assertSame(Visibility::IS_PUBLIC, $prop->visibility);
        self::assertNull($prop->docComment);

        self::assertArrayHasKey('isArray', $class->properties);
        $prop = $class->properties['isArray'];
        self::assertSame('isArray', $prop->name);
        self::assertSame([1, 2, 3 => 4, 'name' => 'me'], $prop->default);
        self::assertFalse($prop->isStatic);
        self::assertSame(32, $prop->line);
        self::assertSame(Visibility::IS_PUBLIC, $prop->visibility);
        self::assertNull($prop->docComment);

        // -------------------------------------------
        // methods
        // -------------------------------------------

        self::assertCount(2, $class->methods);

        self::assertArrayHasKey('calc', $class->methods);
        $method = $class->methods['calc'];
        self::assertSame('calc', $method->name);
        self::assertSame(40, $method->startLine);
        self::assertSame(43, $method->endLine);
        self::assertFalse($method->isStatic);
        self::assertFalse($method->isAbstract);
        self::assertFalse($method->isFinal);
        self::assertSame('int', $method->returnType);
        self::assertSame(Visibility::IS_PUBLIC, $method->visibility);
        $expectedComment            = new RefComment();
        $expectedComment->startLine = 34;
        $expectedComment->endLine   = 39;
        $expectedComment->text      = '/**
     * @param int $a
     * @param int $b
     *
     * @return int
     */';
        self::assertEquals($expectedComment, $method->docComment);

        self::assertCount(2, $method->params);
        self::assertArrayHasKey('a', $method->params);
        $param = $method->params['a'];
        self::assertSame('a', $param->name);
        self::assertNull($param->default);
        self::assertSame('int', $param->type);
        self::assertFalse($param->byRef);
        self::assertFalse($param->isVariadic);

        self::assertArrayHasKey('b', $method->params);
        $param = $method->params['b'];
        self::assertSame('b', $param->name);
        self::assertSame(1, $param->default);
        self::assertSame('int', $param->type);
        self::assertTrue($param->byRef);
        self::assertFalse($param->isVariadic);

        self::assertArrayHasKey('foo', $class->methods);
        $method = $class->methods['foo'];
        self::assertSame('foo', $method->name);
        self::assertSame(45, $method->startLine);
        self::assertSame(47, $method->endLine);
        self::assertTrue($method->isStatic);
        self::assertFalse($method->isAbstract);
        self::assertTrue($method->isFinal);
        self::assertNull($method->returnType);
        self::assertSame(Visibility::IS_PROTECTED, $method->visibility);
        self::assertNull($method->docComment);

        self::assertCount(2, $method->params);
        self::assertArrayHasKey('dt', $method->params);
        $param = $method->params['dt'];
        self::assertSame('dt', $param->name);
        self::assertNull($param->default);
        self::assertSame('DateTime', $param->type);
        self::assertFalse($param->byRef);
        self::assertFalse($param->isVariadic);

        self::assertArrayHasKey('rest', $method->params);
        $param = $method->params['rest'];
        self::assertSame('rest', $param->name);
        self::assertNull($param->default);
        self::assertNull($param->type);
        self::assertFalse($param->byRef);
        self::assertTrue($param->isVariadic);
    }
}
