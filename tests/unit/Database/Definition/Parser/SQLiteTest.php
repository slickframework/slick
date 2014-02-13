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
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
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
            array(
                'type' => 'table',
                'name' => 'users',
                'tbl_name' => 'users',
                'rootpage' => '4',
                'sql' => 'CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" TEXT NOT NULL,
    "full_name" TEXT,
    "active" INTEGER,
    "file" BLOB,
    "avg" REAL,
    "created" TEXT NOT NULL,
    "author_id" INTEGER,
    CONSTRAINT `author`
        FOREIGN KEY (`author_id`)
        REFERENCES `users` (`id`)
        ON DELETE CASCADE 
        ON UPDATE NO ACTION,
    CONSTRAINT `profile`
        FOREIGN KEY (`profile_id`)
        REFERENCES `profile` (`id`)
        ON DELETE SET NULL
        ON UPDATE RESTRICT 
)'
            ),
            array(
                'type' => 'index',
                'name' => 'name_idx',
                'tbl_name' => 'users',
                'rootpage' => '2',
                'sql' => 'CREATE UNIQUE INDEX "name_idx" on users (name ASC)'
            ),
            array(
                'type' => 'index',
                'name' => 'avg_idx',
                'tbl_name' => 'users',
                'rootpage' => '5',
                'sql' => 'CREATE INDEX "avg_idx" on users (name ASC)'
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
        $this->assertEquals('users', $data[0]['name']);
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
        $active = $columns->findByName('active');
        $avg = $columns->findByName('avg');
        $file = $columns->findByName('file');
        $id = $columns->findByName('id');
        $name = $columns->findByName('name');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Column', $fullName);
        $this->assertEquals(Column::TYPE_TEXT, $fullName->getType());
        $this->assertEquals(Column::TYPE_INTEGER, $active->getType());
        $this->assertEquals(Column::TYPE_FLOAT, $avg->getType());
        $this->assertEquals(Column::TYPE_BLOB, $file->getType());
        $this->assertTrue($id->isPrimaryKey());
        $this->assertTrue($id->isAutoIncrement());
        $this->assertTrue($id->isNotNull());
        $this->assertTrue($name->isNotNull());
        $this->assertFalse($fullName->isNotNull());
    }

    /**
     * Retrieve the indexes from parser
     * @test
     */
    public function getIndexes()
    {
        $indexes = $this->_sqlite->getIndexes();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $indexes);
        $name = $indexes->findByName('name_idx');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\Index', $name);
        $this->assertEquals(Index::UNIQUE, $name->getType());
        $this->assertEquals(array('name'), $name->getIndexColumns());

    }

    /**
     * Retrives the foreign keys constraints
     * @test
     */
    public function getForeignKeys()
    {
        $frks = $this->_sqlite->getForeignKeys();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $frks);
        $author = $frks->findByName('author');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ForeignKey', $author);
        $this->assertEquals(array('author_id' => 'id'), $author->getIndexColumns());
        $this->assertEquals('users', $author->getReferencedTable());
        $this->assertEquals(ForeignKey::CASCADE, $author->getOnDelete());
        $this->assertEquals(ForeignKey::NO_ACTION, $author->getOnUpdate());

        $profile = $frks->findByName('profile');
        $this->assertEquals(ForeignKey::SET_NULL, $profile->getOnDelete());
        $this->assertEquals(ForeignKey::RESTRICT, $profile->getOnUpdate());
    }
}