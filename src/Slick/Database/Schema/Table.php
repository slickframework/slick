<?php

/**
 * Table
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema;

use Slick\Database\Schema;
use Slick\Common\BaseMethods;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Table definition for schema
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property ColumnInterface[]     $columns
 * @property ConstraintInterface[] $constraints
 * @property SchemaInterface       $schema
 * @property string                $name
 *
 * @method Table setName(string $name) Sets table name
 */
class Table implements TableInterface
{

    /**
     * Factory behavior methods from Slick\Common\Base class
     */
    use BaseMethods;

    /**
     * @readwrite
     * @var ColumnInterface[]
     */
    protected $_columns = [];

    /**
     * @@readwrite
     * @var ConstraintInterface[]
     */
    protected $_constraints = [];

    /**
     * @readwrite
     * @var
     */
    protected $_schema;

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * A table is created with at minimum a name
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        $this->_name = $name;
        $this->_createObject($options);
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return Table
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->_columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Sets the columns for this table
     *
     * @param ColumnInterface[] $columns
     * @return Table
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $col) {
            $this->addColumn($col);
        }
        return $this;
    }

    /**
     * Returns the current list of columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Add a constraint to this table
     *
     * @param ConstraintInterface $constraint
     *
     * @return Table
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->_constraints[$constraint->getName()] = $constraint;
        return $this;
    }

    /**
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return $this->_constraints;
    }

    /**
     * Sets current list of constraints
     *
     * @param ConstraintInterface[] $constraints
     * @return Table
     */
    public function setConstraints(array $constraints)
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
        return $this;
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
     * Sets the schema for this table
     *
     * @param Schema $schema
     * @return Table
     */
    public function setSchema(Schema $schema)
    {
        $this->_schema = $schema;
        return $this;
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
}