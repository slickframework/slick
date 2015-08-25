<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\Ddl\Constraint\ForeignKey;
use Slick\Database\Sql\Ddl\Constraint\Primary;
use Slick\Database\Sql\Ddl\Constraint\Unique;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * AlterTable SQL template
 *
 * @package Slick\Database\Sql\Dialect\Mysql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTableSqlTemplate extends CreateTableSqlTemplate implements
    SqlTemplateInterface
{
    /**
     * @var string[]
     */
    protected $statements = [];

    /**
     * @var SqlInterface|AlterTable
     */
    protected $sql;

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
        if ($droppedConstraints) {
            $this->statements[] =
                "ALTER TABLE {$tableName} " .
                "{$droppedConstraints}";
        }
        $addColumns = $this->addColumnsStatement();
        if ($addColumns) {
            $this->statements[] = "ALTER TABLE {$tableName} " .
                "ADD ({$addColumns})";
        }
        $changedColumns = $this->changedColumnsStatement();
        if ($changedColumns) {
            $this->statements[] = "ALTER TABLE {$tableName} " .
                "CHANGE COLUMN {$changedColumns}";
        }
        $droppedColumns = $this->droppedColumnsStatement();
        if ($droppedColumns) {
            $this->statements[] = "ALTER TABLE {$tableName} " .
                "DROP COLUMN {$droppedColumns}";
        }
        $constraints = $this->constraintsStatement();
        if ($constraints) {
            $this->statements[] = "ALTER TABLE {$tableName} " .
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
        $constraints = null;
        if (!empty($cons)) {
            $constraints = $this->parseConstraints();
        }
        return $constraints;
    }

    /**
     * Parse column list for SQL drop constraint statement
     *
     * @return null|string
     */
    protected function droppedConstraintsStatement()
    {
        $columns = $this->sql->getDroppedConstraints();
        $droppedConstraints = null;
        if (!empty($columns)) {
            $commands = [];
            foreach ($columns as $constraint) {
                if ($constraint instanceof Primary) {
                    $commands[] = 'DROP PRIMARY KEY';
                }
                if ($constraint instanceof ForeignKey) {
                    $commands[] = 'DROP FOREIGN KEY '. $constraint->getName();
                }
                if ($constraint instanceof Unique) {
                    $commands[] = 'DROP INDEX '. $constraint->getName();
                }
            }
            $droppedConstraints = implode(', ', $commands);
        }

        return $droppedConstraints;
    }

    /**
     * Parse column list for SQL drop column statement
     *
     * @return null|string
     */
    protected function droppedColumnsStatement()
    {
        $columns = $this->sql->getDroppedColumns();
        $droppedColumns = null;
        if (!empty($columns)) {
            $droppedColumns =  implode(', DROP COLUMN ', array_keys($columns));
        }
        return $droppedColumns;
    }

    /**
     * Parse column list for SQL alter column statement
     *
     * @return null|string
     */
    protected function changedColumnsStatement()
    {
        $columns = $this->sql->getChangedColumns();
        $changedColumns = null;
        if (!empty($columns)) {
            $changedColumns = $this->parseChangedColumns();
        }
        return $changedColumns;
    }

    /**
     * Parse column list for SQL add column statement
     *
     * @return null|string
     */
    protected function addColumnsStatement()
    {
        $columns = $this->sql->getColumns();
        $parsedColumns = null;
        if (!empty($columns)) {
            $parsedColumns = $this->parseColumns();
        }
        return $parsedColumns;
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
            $parts[] = $column->getName() .' '. $this->parseColumn($column);
        }
        return implode(', CHANGE COLUMN ', $parts);
    }
}
