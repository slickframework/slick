<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Schema;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Schema\Table;
use Slick\Database\Sql\Ddl\Column\Integer;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Column\Varchar;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Dialect;

/**
 * Class Table Test case
 *
 * @package Slick\Tests\Database\Schema
 */
class TableTest extends TestCase
{

    /**
     * @var Table
     */
    protected $table;

    protected $sql = <<<EOQ
CREATE TABLE  (id BIGINT NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, CONSTRAINT pkUsers PRIMARY KEY (id), CONSTRAINT usernameUnique UNIQUE (username))
EOQ;


    protected function setUp()
    {
        parent::setUp();
        $this->table = new Table();
        $this->prepareTable();
    }

    public function testSqlOutput()
    {
        $this->assertEquals($this->sql, $this->table->getCreateStatement());
    }

    private function prepareTable()
    {
        $this->table->setSchema($this->getSchema());
        $this->table
            ->addColumn(
                new Integer(
                    'id',
                    ['autoIncrement' => true, 'size' => Size::big()]
                )
            )
            ->addColumn(new Text('name', ['size' => Size::tiny()]))
            ->addColumn(new Varchar('username', 255))

            ->addConstraint(new Primary('pkUsers', ['columnNames' => ['id']]))
            ->addConstraint(
                new Unique('usernameUnique', ['column' => 'username'])
            );
    }

    private function getSchema()
    {
        /** @var SchemaInterface|MockObject $schema */
        $schema = $this->getMockBuilder(
            'Slick\Database\Schema'
        )
            ->setMethods(['getAdapter'])
            ->getMock();
        $schema->method('getAdapter')
            ->willReturn($this->getAdapter());
        return $schema;
    }

    /**
     * @return MockObject|AdapterInterface
     */
    private function getAdapter()
    {
        /** @var AdapterInterface|MockObject $adapter */
        $adapter = $this->getMockBuilder(
            'Slick\Database\Adapter\AdapterInterface'
        )
            ->getMock();
        $adapter->method('getDialect')
            ->willReturn(Dialect::STANDARD);
        return $adapter;
    }
}
