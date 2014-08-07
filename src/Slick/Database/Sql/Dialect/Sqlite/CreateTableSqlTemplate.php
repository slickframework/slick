<?php

/**
 * Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\Standard;
use Slick\Database\Sql\SqlInterface;
use Slick\Utility\ArrayMethods;

/**
 * Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends Standard\CreateTableSqlTemplate
{

    /**
     * @var bool Flag for disabling primary key constraint declaration
     */
    protected $_disablePrimaryConstraint = false;

    /**
     * @var string[]
     */
    protected $_afterCreate = [];

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        /** @var CreateTable $sql */
        $this->_sql = $sql;
        $tableName = $this->_sql->getTable();
        $template = "CREATE TABLE %s (%s)";
        $parts = ArrayMethods::clean(
            [$this->_parseColumns(), $this->_parseConstraints()]
        );
        $query = [];
        $query[] = sprintf(
            $template,
            $tableName,
            implode(', ', $parts)
        );
        foreach ($this->_afterCreate as $sql) {
            $query[] = $sql;
        }
        return implode(';', $query);
    }

    /**
     * Parses an integer column to its SQL representation
     *
     * @param Column\Integer $column
     * @return string
     */
    protected function _getIntegerColumn(Column\Integer $column)
    {

        if ($column->getAutoIncrement()) {
            $this->_disablePrimaryConstraint = true;
            return $column->getName().' INTEGER PRIMARY KEY AUTOINCREMENT';
        }

        $type = 'INTEGER';

        $default = '';
        if ($column->getDefault()) {
            $default = ' DEFAULT '.$column->getDefault();
        }

        return sprintf(
            '%s %s%s%s%s',
            $column->getName(),
            $type,
            $this->_columnLength($column),
            $this->_nullableColumn($column),
            $default
        );
    }

    /**
     * Parse a Primary Key constraint to its SQL representation
     *
     * @param Constraint\Primary $constraint
     * @return string
     */
    protected function _getPrimaryConstraint(Constraint\Primary $constraint)
    {
        $stm = parent::_getPrimaryConstraint($constraint);
        if ($this->_disablePrimaryConstraint) {
            $stm = null;
        }
        return $stm;
    }

    /**
     * Parses a Blob column to its SQL representation
     *
     * @param Column\Blob $column
     * @return string
     */
    protected function _getBlobColumn(Column\Blob $column)
    {
        return sprintf(
            '%s NONE',
            $column->getName()
        );
    }

    /**
     * Parses a DateTime column to its SQL representation
     *
     * @param Column\DateTime $column
     * @return string
     */
    protected function _getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s TEXT%s',
            $column->getName(),
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parses a Float column to its SQL representation
     *
     * @param Column\Float $column
     * @return string
     */
    protected function _getFloatColumn(Column\Float $column)
    {
        return sprintf(
            '%s REAL',
            $column->getName()
        );
    }

    /**
     * Parses a boolean column to its SQL representation
     *
     * @param Column\Boolean $column
     * @return string
     */
    protected function _getBooleanColumn(Column\Boolean $column)
    {
        return sprintf(
            '%s INTEGER',
            $column->getName()
        );
    }

    /**
     * Parses a varchar column to its SQL representation
     *
     * @param Column\Varchar $column
     * @return string
     */
    protected function _getVarcharColumn(Column\Varchar $column)
    {
        return sprintf(
            '%s TEXT NOT NULL',
            $column->getName()
        );
    }

    /**
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function _getTextColumn(Column\Text $column)
    {
        $type = 'TEXT';

        return sprintf(
            '%s %s%s',
            $column->getName(),
            $type,
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parse a Unique constraint to its SQL representation
     *
     * @param Constraint\Unique $constraint
     * @return null|string
     */
    protected function _getUniqueConstraint(Constraint\Unique $constraint)
    {
        $this->_afterCreate[] = sprintf(
            'CREATE UNIQUE INDEX %s ON %s(%s)',
            $constraint->getName(),
            $this->_sql->getTable(),
            $constraint->getColumn()
        );
        return null;
    }
}
