<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Alter Table SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTableSqlTemplate extends CreateTableSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * @var SqlInterface|AlterTable
     */
    protected $sql;

    /**
     * @var string[]
     */
    protected $statements = [];

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|AlterTable $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        $tableName = $sql->getTable();
        $droppedConstraints = $this->droppedConstraintsStatement();
        if ($droppedConstraints !== null) {
            $this->statements[] =
                "ALTER"." TABLE {$tableName} DROP CONSTRAINT " .
                "({$droppedConstraints})";
        }
        $addColumns = $this->addColumnsStatement();
        if ($addColumns !== null) {
            $this->statements[] = "ALTER"." TABLE {$tableName} " .
                "ADD ({$addColumns})";
        }
        $changedColumns = $this->changedColumnsStatement();
        if ($changedColumns !== null) {
            $this->statements[] = "ALTER"." TABLE {$tableName} " .
                "ALTER COLUMN ({$changedColumns})";
        }
        $droppedColumns = $this->droppedColumnsStatement();
        if ($droppedColumns !== null) {
            $this->statements[] = "ALTER"." TABLE {$tableName} " .
                "DROP COLUMN ({$droppedColumns})";
        }
        $constraints = $this->constraintsStatement();
        if ($constraints !== null) {
            $this->statements[] = "ALTER"." TABLE {$tableName} " .
                "ADD ({$constraints})";
        }
        return implode(';', $this->statements);
    }

    /**
     * Parse column list for SQL add constraint statement
     *
     * @return null|string
     */
    protected function constraintsStatement()
    {
        $cons = $this->sql->getConstraints();
        if (empty($cons)) {
            return null;
        }
        return $this->parseConstraints();
    }

    /**
     * Parse column list for SQL drop constraint statement
     *
     * @return null|string
     */
    protected function droppedConstraintsStatement()
    {
        $columns = $this->sql->getDroppedConstraints();
        if (empty($columns)) {
            return null;
        }
        return implode(', ', array_keys($columns));
    }

    /**
     * Parse column list for SQL drop column statement
     *
     * @return null|string
     */
    protected function droppedColumnsStatement()
    {
        $columns = $this->sql->getDroppedColumns();
        if (empty($columns)) {
            return null;
        }
        return implode(', ', array_keys($columns));
    }

    /**
     * Parse column list for SQL alter column statement
     *
     * @return null|string
     */
    protected function changedColumnsStatement()
    {
        $columns = $this->sql->getChangedColumns();
        if (empty($columns)) {
            return null;
        }
        return $this->parseChangedColumns();
    }
    /**
     * Parse column list for SQL add column statement
     *
     * @return null|string
     */
    protected function addColumnsStatement()
    {
        $columns = $this->sql->getColumns();
        if (empty($columns)) {
            return null;
        }
        return $this->parseColumns();
    }
    /**
     * Parse column list for SQL alter column statement
     *
     * @return string
     */
    protected function parseChangedColumns()
    {
        $columns = $this->sql->getChangedColumns();
        $parts = [];
        foreach ($columns as $column) {
            $parts[] = $this->parseColumn($column);
        }
        return implode(', ', $parts);
    }
}
