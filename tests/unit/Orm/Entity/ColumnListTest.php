<?php

/**
 * ColumnList test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm\Entity;

use Codeception\Util\Stub;
use Slick\Orm\Entity\Column;
use Slick\Orm\Entity\ColumnList;

/**
 * ColumnList test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ColumnListTest extends \Codeception\TestCase\Test
{

    /**
     * Creates a column list
     * @test
     * @expectedException \Slick\Orm\Exception\InvalidArgumentException
     */
    public function createColumnList()
    {
        $list = new ColumnList();
        $column = new Column(['name' => 'test']);
        $list[] = $column;
        $this->assertSame($list['test'], $column);
        /** @var $list ColumnList */
        $this->assertFalse($list->hasPrimaryKey());
        $this->assertTrue($list->hasColumn('test'));
        $this->assertFalse($list->hasColumn('other'));
        $primary = new Column(['name' => 'foo', 'primaryKey' => true]);
        $list->append($primary);
        $this->assertTrue($list->hasPrimaryKey());
        $this->assertSame($primary, $list->get('foo'));
        $this->assertNull($list->get('bar'));
        $list[] = new \StdClass();
    }

}