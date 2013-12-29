<?php

/**
 * Element list test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl\Utility;

use Codeception\Util\Stub,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\ElementList;

/**
 * Element list test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ElementListTest extends \Codeception\TestCase\Test
{

    /**
     * Add elemtes to the list
     * @test
     * @expectedException Slick\Database\Exception\InvalidArgumentException
     */
    public function addElementsToList()
    {
        $list = new ElementList();
        $column = new Column('test');
        $lis[] = $column;
        $colum2 = new Column('test2');
        $this->assertFalse($list->contains($colum2));
        $list[] = new \StdClass();
    }

}