<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Common\Utils\ArrayMethods;
use Slick\Database\Sql\Ddl\CreateTable;
use Slick\Database\Sql\Dialect\Standard;
use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\SqlInterface;

/**
 * Class CreateTableSqlTemplate
 *
 * @package Slick\Database\Sql\Dialect\Sqlite
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends Standard\CreateTableSqlTemplate
{

    /**
     * @var bool Flag for disabling primary key constraint declaration
     */
    protected $disablePrimaryConstraint = false;

    /**
     * @var string[]
     */
    protected $afterCreate = [];

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
        $this->sql = $sql;
        $tableName = $this->sql->getTable();
        $template = "CREATE TABLE %s (%s)";
        $parts = ArrayMethods::clean(
            [$this->parseColumns(), $this->parseConstraints()]
        );
        $query = [];
        $query[] = sprintf(
            $template,
            $tableName,
            implode(', ', $parts)
        );
        foreach ($this->afterCreate as $sql) {
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
    protected function getIntegerColumn(Column\Integer $column)
    {
        if ($column->getAutoIncrement()) {
            $this->disablePrimaryConstraint = true;
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
            $this->columnLength($column),
            $this->nullableColumn($column),
            $default
        );
    }

    /**
     * Parse a Primary Key constraint to its SQL representation
     *
     * @param Constraint\Primary $constraint
     * @return string
     */
    protected function getPrimaryConstraint(Constraint\Primary $constraint)
    {
        $stm = parent::getPrimaryConstraint($constraint);
        if ($this->disablePrimaryConstraint) {
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
    protected function getBlobColumn(Column\Blob $column)
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
    protected function getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s TEXT%s',
            $column->getName(),
            $this->nullableColumn($column)
        );
    }

    /**
     * Parses a Float column to its SQL representation
     *
     * @param Column\Decimal $column
     * @return string
     */
    protected function getFloatColumn(Column\Decimal $column)
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
    protected function getBooleanColumn(Column\Boolean $column)
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
    protected function getVarcharColumn(Column\Varchar $column)
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
    protected function getTextColumn(Column\Text $column)
    {
        $type = 'TEXT';
        return sprintf(
            '%s %s%s',
            $column->getName(),
            $type,
            $this->nullableColumn($column)
        );
    }

    /**
     * Parse a Unique constraint to its SQL representation
     *
     * @param Constraint\Unique $constraint
     * @return null|string
     */
    protected function getUniqueConstraint(Constraint\Unique $constraint)
    {
        $this->afterCreate[] = sprintf(
            'CREATE UNIQUE INDEX %s ON %s(%s)',
            $constraint->getName(),
            $this->sql->getTable(),
            $constraint->getColumn()
        );
        return null;
    }
}
