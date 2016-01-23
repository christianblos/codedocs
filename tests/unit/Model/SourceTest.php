<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\Model\Source;

/**
 * @covers \CodeDocs\Model\Source
 */
class SourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Source
     */
    private $source;

    /**
     *
     */
    protected function setUp()
    {
        $this->source = new Source('baseDir', 'docsDir', ['classDir1', 'classDir2']);
    }

    /**
     * @test
     */
    public function contains_base_dir()
    {
        $this->assertEquals('baseDir', $this->source->getBaseDir());
    }

    /**
     * @test
     */
    public function contains_docs_dir()
    {
        $this->assertEquals('docsDir', $this->source->getDocsDir());
    }

    /**
     * @test
     */
    public function contains_class_dirs()
    {
        $this->assertEquals(['classDir1', 'classDir2'], $this->source->getClassDirs());
    }
}
