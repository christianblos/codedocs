<?php
namespace CodeDocs\Test\ValueObject;

use CodeDocs\ValueObject\ItemList;

/**
 * @covers \CodeDocs\ValueObject\ItemList
 */
class ItemListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function lists_items_as_bullet_points_by_default()
    {
        $items = ['first', 'second'];
        $list  = new ItemList($items);

        $expected = '- ' . implode("\n- ", $items);

        $this->assertEquals($expected, (string)$list);
    }

    /**
     * @test
     */
    public function seperates_items_by_given_glue()
    {
        $list = new ItemList(['first', 'second'], ', ');

        $this->assertEquals('first, second', (string)$list);
    }
}
