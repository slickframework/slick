<?php

/**
 * Schema test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Schema;

use Slick\Database\Adapter\MysqlAdapter;
use Slick\Database\Adapter;
use Slick\Database\Schema;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Schema\TableInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;
use Slick\Database\Sql\Dialect;

/**
 * Schema test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SchemaTest extends \Codeception\TestCase\Test
{

    /**
     * @var MysqlAdapter
     */
    protected $_adapter;

    /**
     * Creates the adapter for tests
     */
    protected function _before()
    {
        parent::_before();
        $adapter = new Adapter([
            'driver' => 'Mysql',
            'options' => [
                'autoConnect' => false,
                'database' => 'mySchemaTest'
            ]
        ]);
        $this->_adapter = $adapter->initialize();
    }

    /**
     * Removes the adapter for the next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Create a schema object
     * @test
     */
    public function createSchema()
    {
        $schema = new Schema(['adapter' => $this->_adapter]);
        $this->assertInstanceOf('Slick\Database\Schema\SchemaInterface', $schema);

        $this->assertSame($this->_adapter, $schema->getAdapter());
        $this->assertEquals('mySchemaTest', $schema->getName());

        $this->assertInstanceOf('Slick\Database\Schema', $schema->setAdapter($this->_adapter));
    }

    /**
     * Adding tables to schema
     * @test
     */
    public function addTablesToSchema()
    {
        $schema = new Schema(['adapter' => $this->_adapter]);

        $tableA = new MyTable('A');
        $tableB = new MyTable('B');
        $tableC = new MyTable('C');

        $result = $schema->addTable($tableA);
        $this->assertInstanceOf('Slick\Database\Schema', $result);
        $this->assertEquals($schema, $schema->getTables()['A']->getSchema());

        $result = $schema->setTables([$tableA, $tableB, $tableC]);
        $this->assertInstanceOf('Slick\Database\Schema', $result);
        $this->assertEquals($schema, $schema->getTables()['C']->getSchema());
    }

    /**
     * Retrieve creation of a Schema SQL
     * @test
     */
    public function getSchemaSql()
    {
        /** @var Schema $schema */
        $schema = include(__DIR__ .'/schemaDef.php');
        $this->assertInstanceOf('Slick\Database\Schema', $schema);
        $schema->setAdapter($this->_adapter);

        $sql = include(__DIR__ .'/schemaDump.php');
        $this->assertEquals($sql, $schema->getCreateStatement());
    }
}

class MyTable implements Schema\TableInterface
{

    /**
     * @var Schema
     */
    protected $_schema;

    /**
     * @var string
     */
    protected $_name;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return TableInterface
     */
    public function addColumn(ColumnInterface $column)
    {
        // not needed
    }

    /**
     * @param ConstraintInterface $constraint
     *
     * @return TableInterface
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        // not needed
    }

    /**
     * Returns the current list of columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        // not needed
    }

    /**
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        // not needed
    }

    /**
     * Set table schema
     *
     * @return SchemaInterface
     */
    public function getSchema()
    {
        return $this->_schema;
    }

    /**
     * Returns table name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the schema for this table
     *
     * @param Schema $schema
     * @return TableInterface
     */
    public function setSchema(Schema $schema)
    {
        $this->_schema = $schema;
        return $this;
    }
}