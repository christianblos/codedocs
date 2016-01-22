<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\ValueObject\Directory;

/**
 * @covers \CodeDocs\ValueObject\Directory
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function root_dir_must_exist()
    {
        new Directory('dir', 'dir');
    }

    /**
     * @test
     */
    public function ignores_root_dir_if_no_dot()
    {
        $dir = new Directory('some', __DIR__);

        $this->assertEquals('some', (string)$dir);
    }

    /**
     * @test
     */
    public function prepend_root_dir_if_dot()
    {
        $dir = new Directory('./some', __DIR__);

        $this->assertEquals(__DIR__ . '/./some', (string)$dir);
    }

    /**
     * @test
     */
    public function is_root_dir_if_only_dot()
    {
        $dir = new Directory('.', __DIR__);

        $this->assertEquals(__DIR__, (string)$dir);
    }

    /**
     * @test
     */
    public function prepend_root_dir_if_back()
    {
        $dir = new Directory('../some', __DIR__);

        $this->assertEquals(__DIR__ . '/../some', (string)$dir);
    }

    /**
     * @test
     */
    public function prepend_root_dir_if_only_back()
    {
        $dir = new Directory('..', __DIR__);

        $this->assertEquals(__DIR__ . '/..', (string)$dir);
    }

}
