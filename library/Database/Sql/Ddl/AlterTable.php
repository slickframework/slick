<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Alter Table SQL statement
 *
 * @package Slick\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTable extends CreateTable implements SqlInterface
{

    /**
     * @var ColumnInterface[]
     */
    protected $changedColumns = [];

    /**
     * @var ColumnInterface[]
     */
    protected $droppedColumns = [];

    /**
     * @var ConstraintInterface[]
     */
    protected $dropConstraints = [];

    /**
     * Add a column to the list of columns that will be changed
     *
     * @param ColumnInterface $column
     * @return AlterTable
     */
    public function changeColumn(ColumnInterface $column)
    {
        $this->changedColumns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Returns the columns to be changed
     *
     * @return Column\ColumnInterface[]
     */
    public function getChangedColumns()
    {
        return $this->changedColumns;
    }

    /**
     * Add a column to the list of columns that will be deleted
     *
     * @param ColumnInterface $column
     * @return AlterTable
     */
    public function dropColumn($column)
    {
        $this->droppedColumns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Returns the columns to be deleted
     *
     * @return ColumnInterface[]
     */
    public function getDroppedColumns()
    {
        return $this->droppedColumns;
    }

    /**
     * Add a constraint to the list of constraint that will be deleted
     *
     * @param ConstraintInterface $cons
     * @return AlterTable
     */
    public function dropConstraint(ConstraintInterface $cons)
    {
        $this->dropConstraints[$cons->getName()] = $cons;
        return $this;
    }

    /**
     * Returns the list of dropped constraints
     *
     * @return Constraint\ConstraintInterface[]
     */
    public function getDroppedConstraints()
    {
        return $this->dropConstraints;
    }

    /**
     * Adds a constraint to the list of constraints
     *
     * @param ConstraintInterface $constraint
     *
     * @return AlterTable
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        return parent::addConstraint($constraint);
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return AlterTable
     */
    public function addColumn(ColumnInterface $column)
    {
        return parent::addColumn($column);
    }
}
