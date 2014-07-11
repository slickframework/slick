<?php

/**
 * Standard Alter Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * Standard Alter Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTableSqlTemplate extends CreateTableSqlTemplate implements
    SqlTemplateInterface
{
    /**
     * @var string[]
     */
    protected $_statements = [];

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|AlterTable $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        $tableName = $sql->getTable();

        $droppedConstraints = $this->_droppedConstraintsStatement();
        if ($droppedConstraints) {
            $this->_statements[] =
                "ALTER TABLE {$tableName} DROP CONSTRAINT " .
                "({$droppedConstraints})";
        }

        $addColumns = $this->_addColumnsStatement();
        if ($addColumns) {
            $this->_statements[] = "ALTER TABLE {$tableName} " .
                "ADD ({$addColumns})";
        }

        $changedColumns = $this->_changedColumnsStatement();
        if ($changedColumns) {
            $this->_statements[] = "ALTER TABLE {$tableName} " .
                "ALTER COLUMN ({$changedColumns})";
        }

        $droppedColumns = $this->_droppedColumnsStatement();
        if ($droppedColumns) {
            $this->_statements[] = "ALTER TABLE {$tableName} " .
                "DROP COLUMN ({$droppedColumns})";
        }

        $constraints = $this->_constraintsStatement();
        if ($constraints) {
            $this->_statements[] = "ALTER TABLE {$tableName} " .
                "ADD ({$constraints})";
        }
        return implode(';', $this->_statements);
    }

    /**
     * Parse column list for SQL add constraint statement
     *
     * @return null|string
     */
    protected function _constraintsStatement()
    {
        $cons = $this->_sql->getConstraints();
        if (empty($cons)) {
            return null;
        }

        return $this->_parseConstraints();
    }

    /**
     * Parse column list for SQL drop constraint statement
     *
     * @return null|string
     */
    protected function _droppedConstraintsStatement()
    {
        $columns = $this->_sql->getDroppedConstraints();
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
    protected function _droppedColumnsStatement()
    {
        $columns = $this->_sql->getDroppedColumns();
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
    protected function _changedColumnsStatement()
    {
        $columns = $this->_sql->getChangedColumns();
        if (empty($columns)) {
            return null;
        }

        return $this->_parseChangedColumns();
    }

    /**
     * Parse column list for SQL add column statement
     *
     * @return null|string
     */
    protected function _addColumnsStatement()
    {
        $columns = $this->_sql->getColumns();
        if (empty($columns)) {
            return null;
        }

        return $this->_parseColumns();
    }

    /**
     * Parse column list for SQL alter column statement
     *
     * @return string
     */
    protected function _parseChangedColumns()
    {
        $columns = $this->_sql->getChangedColumns();
        $parts = [];
        foreach ($columns as $column) {
            $parts[] = $this->_parseColumn($column);
        }
        return implode(', ', $parts);
    }
}
