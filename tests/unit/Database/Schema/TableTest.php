<?php

/**
 * Table test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Schema;

use Slick\Database\Adapter;
use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\Column\Text;
use Slick\Database\Sql\Ddl\Constraint\Unique;

/**
 * Table test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TableTest extends \Codeception\TestCase\Test
{
    /**
     * @var Schema
     */
    protected $_schema;

    /**
     * Runs before every test
     */
    protected function _before()
    {
        parent::_before();
        $adapter = new Adapter([
            'driver' => 'Mysql',
            'options' => [
                'autoConnect' => false,
                'database' => 'MyTableTest'
            ]
        ]);
        $adapter = $adapter->initialize();
        $this->_schema = new Schema(['adapter' => $adapter]);
    }

    /**
     * Clean up things after for next test
     */
    protected function _after()
    {
        unset($this->_schema);
        parent::_after();
    }

    /**
     * Create a table for a schema
     * @test
     */
    public function createTable()
    {
        $table = new Schema\Table('users');
        $this->assertInstanceOf('Slick\Database\Schema\TableInterface', $table);
        $this->assertEquals('users', $table->name);
        $this->assertInstanceOf('Slick\Database\Schema\Table', $table->setName('people'));

        $this->assertInstanceOf('Slick\Database\Schema\Table', $table->setSchema($this->_schema));
        $this->assertEquals($this->_schema, $table->getSchema());
    }

    /**
     * Adding columns to table
     * @test
     */
    public function addColumnsToTable()
    {
        $table = new Schema\Table('people', ['schema' => $this->_schema]);

        $columnA = new Text('A');
        $columnB = new Text('B');
        $result = $table-> addColumn($columnA);
        $this->assertEquals($columnA, $table->getColumns()['A']);
        $this->assertInstanceOf('Slick\Database\Schema\Table', $result);

        $result = $table->setColumns([$columnA, $columnB]);
        $this->assertEquals(['A' => $columnA, 'B' => $columnB], $table->getColumns());
        $this->assertInstanceOf('Slick\Database\Schema\Table', $result);
    }

    /**
     * Add Constraints
     * @test
     */
    public function addConstraintsToTable()
    {
        $table = new Schema\Table('people', ['schema' => $this->_schema]);

        $constraintA = new Unique('a', ['column' => 'foo']);
        $constraintB = new Unique('b', ['column' => 'bar']);
        $constraints = ['a' => $constraintA, 'b' => $constraintB];

        $result = $table->addConstraint($constraintA);
        $this->assertInstanceOf('Slick\Database\Schema\Table', $result);
        $this->assertEquals($constraintA, $table->getConstraints()['a']);

        $result = $table->setConstraints($constraints);
        $this->assertInstanceOf('Slick\Database\Schema\Table', $result);
        $this->assertEquals($constraints, $table->getConstraints());
    }
}