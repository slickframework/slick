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

use Slick\Database\Sql\AbstractSql;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Create Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CrateTable extends AbstractSql implements SqlInterface
{

    /**
     * @var ColumnInterface[]
     */
    protected $_columns = [];

    /**
     * @var array
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
        // TODO: Implement getQueryString() method.
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return CrateTable
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->_columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * @param ConstraintInterface $constraint
     *
     * @return CrateTable
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->_constraints[] = $constraint;
        return $this;
    }
}
