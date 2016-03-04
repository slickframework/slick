<?php

/**
 * Alter Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\ExecuteMethods;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Alter Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTable extends CreateTable implements SqlInterface
{
    /**
     * Use execute methods
     */
    use ExecuteMethods;

    /**
     * @var ColumnInterface[]
     */
    protected $_changedColumns = [];

    /**
     * @var ColumnInterface[]
     */
    protected $_droppedColumns = [];

    /**
     * @var ConstraintInterface[]
     */
    protected $_dropConstraints = [];

    /**
     * Add a column to the list of columns that will be changed
     *
     * @param ColumnInterface $column
     * @return AlterTable
     */
    public function changeColumn(ColumnInterface $column)
    {
        $this->_changedColumns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Returns the columns to be changed
     *
     * @return Column\ColumnInterface[]
     */
    public function getChangedColumns()
    {
        return $this->_changedColumns;
    }

    /**
     * Add a column to the list of columns that will be deleted
     *
     * @param ColumnInterface $column
     * @return AlterTable
     */
    public function dropColumn($column)
    {
        $this->_droppedColumns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Returns the columns to be deleted
     *
     * @return ColumnInterface[]
     */
    public function getDroppedColumns()
    {
        return $this->_droppedColumns;
    }

    /**
     * Add a constraint to the list of constraint that will be deleted
     *
     * @param ConstraintInterface $cons
     * @return AlterTable
     */
    public function dropConstraint(ConstraintInterface $cons)
    {
        $this->_dropConstraints[$cons->getName()] = $cons;
        return $this;
    }

    /**
     * Returns the list of dropped constraints
     *
     * @return Constraint\ConstraintInterface[]
     */
    public function getDroppedConstraints()
    {
        return $this->_dropConstraints;
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
