<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\ValueObject\ContentList;

/**
 * @covers \CodeDocs\ValueObject\ContentList
 */
class ContentListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function lists_contents_one_below_the_other_by_default()
    {
        $contents = ['first', 'second'];
        $list     = new ContentList($contents);

        $expected = implode("\n\n", $contents);

        $this->assertEquals($expected, (string)$list);
    }

    /**
     * @test
     */
    public function seperates_contents_by_given_glue()
    {
        $list = new ContentList(['first', 'second'], ',');

        $this->assertEquals('first,second', (string)$list);
    }
}
