<?php

/**
 * Create Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\AbstractSql;
use Slick\Database\Sql\ExecuteMethods;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Create Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTable extends AbstractSql implements SqlInterface
{

    /**
     * Use execute methods
     */
    use ExecuteMethods;

    /**
     * @var ColumnInterface[]
     */
    protected $_columns = [];

    /**
     * @var ConstraintInterface[]
     */
    protected $_constraints = [];

    /**
     * Creates the sql with the table name
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->_table = $tableName;
    }

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->_adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return CreateTable
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->_columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Adds a new constraint to this sql statement
     *
     * @param ConstraintInterface $constraint
     *
     * @return self
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->_constraints[$constraint->getName()] = $constraint;
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
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return $this->_constraints;
    }

    /**
     * Sets collection of columns
     *
     * @param ColumnInterface[] $columns
     *
     * @return CreateTable
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $col) {
            $this->addColumn($col);
        }
        return $this;
    }

    /**
     * Sets collection of constraints
     *
     * @param ConstraintInterface[] $constraints
     *
     * @return CreateTable
     */
    public function setConstraints(array $constraints)
    {
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
        return $this;
    }
}
