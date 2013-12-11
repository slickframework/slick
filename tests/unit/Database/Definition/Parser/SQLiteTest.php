<?php

/**
 * SQLite parser Test case
 * 
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Definition\Parser;

use Codeception\Util\Stub;
use Slick\Database\RecordList,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Definition\Parser\SQLite;

/**
 * SQLite parser Test case
 * 
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLiteTest extends \Codeception\TestCase\Test
{
    /**
     * The SUT object instance
     * @var \Slick\Database\Definition\Parser\SQLite
     */
    protected $_sqlite;

    /**
     * Creates the SUT instance
     */
    protected function _before()
    {
        parent::_before();
        $rows = array(
            (object) array(
                'type' => 'table',
                'name' => 'users',
                'tbl_name' => 'users',
                'rootpage' => '4',
                'sql' => 'CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT NOT NULL,
    "full_name" TEXT,
    "active" INTEGER,
    "created" TEXT NOT NULL
)'
            ),
            (object) array(
                'type' => 'index',
                'name' => 'name_idx',
                'tbl_name' => 'users',
                'rootpage' => '2',
                'sql' => 'CREATE UNIQUE INDEX "name_idx" on users (name ASC)'
            )
        );

        $data = new RecordList($rows);
        $this->_sqlite = new SQLite(array('data' => $data));
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_sqlite);
        parent::_after();
    }

    /**
     * Create a SQLite parser.
     * @test
     */
    public function createParser()
    {
        $this->assertInstanceOf('Slick\Database\Definition\Parser\SQLite', $this->_sqlite);
        $data = $this->_sqlite->getData();
        $this->assertEquals('users', $data[0]->name);
    }

    /**
     * Retrieve the columns form parser.
     * @test
     */
    public function getColumns()
    {
        $columns = $this->_sqlite->getColumns();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $columns);
        $fullName = $columns->findByName('full_name');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Column', $fullName);
        $this->assertEquals(Column::TYPE_TEXT, $fullName->getType());
    }
}