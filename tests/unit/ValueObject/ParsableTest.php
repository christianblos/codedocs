<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\ValueObject\Parsable;

/**
 * @covers \CodeDocs\ValueObject\Parsable
 */
class ParsableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function returns_same_text()
    {
        $this->assertEquals('some text', (string)new Parsable('some text'));
    }
}
