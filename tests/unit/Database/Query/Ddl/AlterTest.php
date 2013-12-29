<?php

/**
 * ALTER TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;

use Codeception\Util\Stub;
use Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Column;

/**
 * ALTER TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Query\Ddl\Alter
     */
    protected $_alter;

    /**
     * Set the SUT qlter statement
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(array('type' => 'sqlite'));
        $db = $db->initialize();
        $this->_alter = $db->connect()->ddlQuery()->alter('users');
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_alter);
        parent::_after();
    }

    /**
     * Check query creation
     * @test
     */
    public function crateAAlterQuery()
    {
        $this->assertInstanceOf('Slick\Database\Query\Sql\SqlInterface', $this->_alter);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $this->_alter);
    }

    /**
     * Drop a column
     * @test
     */
    public function dropColumn()
    {
        $alter = $this->_alter->dropColumn('name');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $alter);
        $droppedColumns = $alter->droppedColumns;
        $first = reset($droppedColumns);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Column', $first);
        $this->assertEquals('name', $first->name);
    }

    /**
     * Drop foreign key
     * @test
     */
    public function dropForeignKey()
    {
        $alter = $this->_alter->dropForeignKey("author_fk");
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $alter);
        $droppedForeignKeys = $alter->getDroppedForeignKeys();
        $first = reset($droppedForeignKeys);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ForeignKey', $first);
        $this->assertEquals('author_fk', $first->name);
    }

    /**
     * Drop index
     * @test
     */
    public function dropIndex()
    {
        $alter = $this->_alter->dropIndex("name");
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $alter);
        $droppedIndexes = $alter->getDroppedIndexes();
        $first = reset($droppedIndexes);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $first);
        $this->assertEquals('name_idx', $first->name);
    }

    /**
     * Change column by name
     * @test
     */
    public function changeColumnByName()
    {
        $alter = $this->_alter->changeColumn('name', array());
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $alter);
        $changedColumns = $alter->getChangedColumns();
        $first = reset($changedColumns);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Column', $first);
        $this->assertEquals('name', $first->name);
    }

    /**
     * Change column by name
     * @test
     */
    public function changeColumnByColumnObject()
    {
        $col = new Column(array('name' => 'name'));
        $alter = $this->_alter->changeColumn($col);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Alter', $alter);
        $changedColumns = $alter->getChangedColumns();
        $first = reset($changedColumns);
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Column', $first);
        $this->assertEquals('name', $first->name);
    }

}