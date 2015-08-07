<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\AbstractExecutionOnlySql;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;

/**
 * Create Table SQL statement
 *
 * @package Slick\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTable extends AbstractExecutionOnlySql implements SqlInterface
{

    /**
     * @var ColumnInterface[]
     */
    protected $columns = [];

    /**
     * @var ConstraintInterface[]
     */
    protected $constraints = [];

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->adapter->getDialect(), $this);
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
        $this->columns[$column->getName()] = $column;
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
        $this->constraints[$constraint->getName()] = $constraint;
        return $this;
    }

    /**
     * Returns the current list of columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return $this->constraints;
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
