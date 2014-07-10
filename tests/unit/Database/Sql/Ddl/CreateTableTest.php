<?php

/**
 * Create table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl;

use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\Column\Blob;
use Slick\Database\Sql\Ddl\Column\Boolean;
use Slick\Database\Sql\Ddl\Column\DateTime;
use Slick\Database\Sql\Ddl\Column\Float;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Ddl\Constraint\Primary;

/**
 * Create table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableTest extends \Codeception\TestCase\Test
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Prepares for test
     */
    protected function _before()
    {
        parent::_before();
        $this->_adapter = new Adapter(['options' => ['autoConnect' => false]]);
        $this->_adapter = $this->_adapter->initialize();
    }

    /**
     * Cleans for next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Trying to create a Create Table query
     * @test
     */
    public function createTableQuery()
    {
        $ddl = new CreateTable('users');
        $ddl->addColumn(new Integer('id', ['autoIncrement' => true, 'size' => Size::long()]))
            ->addColumn(new Text('name', ['size' => Size::tiny()]))
            ->addColumn(new Varchar('username', 255))
            ->addColumn(new Varchar('password', 128))
            ->addConstraint(new Primary('usersPk', ['columnNames' => ['id']]))
            ->addConstraint(new Unique('usernameUnique', ['column' => 'username']));

        $this->assertInstanceOf('Slick\Database\Sql\Ddl\CreateTable', $ddl);
        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $ddl);

        /** @var Primary[]|Unique[] $constraints */
        $constraints = $ddl->getConstraints();
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Constraint\Unique',
            $constraints['usernameUnique']
        );
        $this->assertEquals('username', $constraints['usernameUnique']->getColumn());

        /** @var Integer[]|Text[]|Varchar[] $columns */
        $columns = $ddl->getColumns();
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Text',
            $columns['name']
        );
        $this->assertEquals(128, $columns['password']->getLength());
    }

    /**
     * Trying to retrieve the SQL from create table object
     * @test
     */
    public function retrieveTheSqlForCreateTable()
    {
        $expected  = "CREATE TABLE users (";
        $expected .= "id BIGINT NOT NULL AUTO_INCREMENT, ";
        $expected .= "age SMALLINT(5), ";
        $expected .= "points INTEGER NOT NULL DEFAULT 100, ";
        $expected .= "name VARCHAR(255) NOT NULL, ";
        $expected .= "username VARCHAR(255) NOT NULL, ";
        $expected .= "password VARCHAR(128) NOT NULL, ";
        $expected .= "active BOOLEAN, ";
        $expected .= "rate FLOAT(3), ";
        $expected .= "score DECIMAL(3, 2), ";
        $expected .= "created TIMESTAMP NOT NULL, ";
        $expected .= "updated TIMESTAMP, ";
        $expected .= "picture BLOB(1024) NOT NULL";
        $expected .= ")";
        /** @var CreateTable $ddl */
        $ddl = new CreateTable('users');
        $ddl->addColumn(
                new Integer('id',
                    ['autoIncrement' => true, 'size' => Size::big()]
                )
            )
            ->addColumn(
                new Integer('age',
                    ['size' => Size::tiny(), 'length' => 5, 'nullable' => true]
                )
            )
            ->addColumn(new Integer('points', ['default' => 100]))
            ->addColumn(new Text('name', ['size' => Size::tiny()]))
            ->addColumn(new Varchar('username', 255))
            ->addColumn(new Varchar('password', 128))
            ->addColumn(new Boolean('active'))
            ->addColumn(new Float('rate', 3))
            ->addColumn(new Float('score', 3, 2))
            ->addColumn(new DateTime('created'))
            ->addColumn(new DateTime('updated', ['nullable' => true]))
            ->addColumn(new Blob('picture', 1024));

        $ddl->setAdapter($this->_adapter);
        $this->assertEquals($expected, $ddl->getQueryString());
    }
}
