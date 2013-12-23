<?php

/**
 * Database Functional test case (Mysql)
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
    Slick\Configuration\Configuration;

/**
 * Database Functional test case (Mysql)
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Connector\Mysql
     */
    protected $_conn;

    /**
     * Creates the database connector for the test
     */
    protected function _before()
    {
        parent::_before();
        $config = new Configuration(
            array(
                'class' => 'ini',
                'options' => array(
                    'file' => __DIR__ . '/mysql.ini',
                )
            )
        );
        $options = $config->initialize()->get('default');
        $conn = new Database(array('type' => 'mysql', 'options' => $options));
        $this->_conn = $conn->initialize()->connect();
    }

    /**
     * Cleanup database connector before new test
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
            );
        $this->assertTrue($query->execute());
        $alter = $ddl->alter('users')
            ->addForeignKey(
                array(
                    'name' => 'author_fk',
                    'referencedTable' => 'users',
                    'indexColumns' => array('author_id' => 'id'),
                    'onDelete' => ForeignKey::SET_NULL
                )
            )
            ->addIndex('author_id');
        $this->assertTrue($alter->execute());
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