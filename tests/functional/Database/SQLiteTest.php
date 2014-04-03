<?php

/**
 * Database Functional test case (SQLite)
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */


namespace Database;

use Codeception\Util\Stub;
use Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
    Slick\Database\Definition\TableDefinition;

/**
 * Database Functional test case (SQLite)
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLiteTest extends \Codeception\TestCase\Test
{
   
   /**
    * @var Database A SQLite database connector
    */
   protected $_conn;

   /**
     * Creates the database connector for the test
     */
    protected function _before()
    {
        parent::_before();
        $conn = new Database(
            array(
                'type' => 'sqlite',
                'options' => array(
                    'file' => __DIR__ . '/sqlite.db'
                )
            )
        );
        $this->_conn = $conn->initialize()->connect();
    }

    /**
     * Clean up for the next test
     */
    protected function _after()
    {
        unset($this->_conn);
        parent::_after();
    }

    /**
     * Creates table users and profile
     * @test
     */
    public function createTables()
    {
        $ddl = $this->_conn->ddlQuery();
        $query = $ddl->create('users')
            ->addColumn(
                'id',
                array(
                    'primaryKey' => true,
                    'autoIncrement' => true,
                    'unsigned' => true,
                    'type' => Column::TYPE_INTEGER,
                    'size' => Column::SIZE_BIG,
                    'notNull' => true
                )
            )
            ->addColumn(
                'userName',
                array(
                    'type' => Column::TYPE_VARCHAR,
                    'length' => 255,
                    'notNull' => true
                )
            )
            ->addIndex(
                'userName',
                array(
                    'type' => Index::UNIQUE
                )
            )
            ->addColumn(
                'password',
                array(
                    'type' => Column::TYPE_VARCHAR,
                    'length' => 40,
                    'notNull' => true
                )
            )
            ->addColumn(
                'author_id',
                array(
                    'type' => Column::TYPE_INTEGER,
                    'size' => Column::SIZE_BIG,
                    'unsigned' => true
                )
            )
            ->addForeignKey(
                array(
                    'name' => 'author_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('author_id' => 'id'),
                    'onDelete' => ForeignKey::SET_NULL
                )
            )
            ->addIndex('author_id');
        $this->assertTrue($query->execute());

        $definition = new TableDefinition('users', $this->_conn);
        $definition->load();

        $columns = $definition->getColumns();
        $userName = $columns->findByName('userName');
        $this->assertEquals(Column::TYPE_VARCHAR, $userName->getType());
        $this->assertEquals(4, count($columns));

        $indexes = $definition->getIndexes();
        $author = $indexes->findByName('author_id_idx');
        $this->assertEquals(Index::INDEX, $author->getType());
        $this->assertEquals(2, count($indexes));

        $foreignKeys = $definition->getForeignKeys();
        $authorFk = $foreignKeys->findByName('author_fk');
        $this->assertEquals('users', $authorFk->referencedTable);
        $this->assertEquals(1, count($foreignKeys));
    }

    /**
     * Try to alter the users table
     * @test
     */
    public function alterTable()
    {
        $alter = $this->_conn->ddlQuery()->alter('users');
        $alter->addColumn(
            'email',
            array(
                'type' => Column::TYPE_VARCHAR,
                'length' => 255,
                'notNull' => true,
                'default' => 'None'
            )
        )
        ->addIndex('email', array('type' => Index::UNIQUE));
        $this->assertTrue($alter->execute());

        $definition = new TableDefinition('users', $this->_conn);
        $definition->load();

        $indexes = $definition->getIndexes();
        $emailIdx = $indexes->findByName('email_idx');
        $this->assertEquals(Index::UNIQUE, $emailIdx->getType());
        $this->assertEquals(3, count($indexes));

        $columns = $definition->getColumns();
        $email = $columns->findByName('email');
        
        // $this->assertEquals(5, count($columns));
        // $this->assertEquals(Column::TYPE_VARCHAR, $email->type);
    }

    /**
     * Try to insert some data into users table
     * @test
     */
    public function insertData()
    {
        $usr = array(
            'userName' => 'fsilva',
            'password' => md5('somePass'),
            'email' => 'fsilva@example.com',
            'author_id' => 1
        );
        $result = $this->_conn->query()->insert('users')->set($usr)->save();
        $this->assertTrue($result);

        $rows = $this->_conn->query()->select('users')->all();
        $this->assertEquals(1, count($rows));
        $usr['id'] = 1;
        $this->assertEquals($usr, $rows[0]);
    }

    /**
     * Change data
     * @test
     */
    public function changeData()
    {
        $usr = array(
            'id' => 1,
            'userName' => 'filipe.silva',
            'password' => md5('somePass'),
            'email' => 'fsilva@example.com',
            'author_id' => 1
        );
        $result = $this->_conn->query()->update('users')
            ->set(array('userName' => 'filipe.silva'))
            ->where(array('id = :id' => array(':id' => 1)))
            ->save();
        $this->assertTrue($result);

        $rows = $this->_conn->query()->select('users')->all();
        $this->assertEquals(1, count($rows));
        $this->assertEquals($usr, $rows[0]);
    }

    /**
     * Test table drop
     * @test
     */
    public function dropTable()
    {
        $ddl = $this->_conn->ddlQuery();
        $drop = $ddl->drop('users');
        $this->assertTrue($drop->execute());
    }

}