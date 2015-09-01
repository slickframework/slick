<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Database\Exception\ServiceException;
use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\SqlInterface;

/**
 * Class AlterTableSqlTemplate
 *
 * @package Slick\Database\Sql\Dialect\Sqlite
 */
class AlterTableSqlTemplate extends CreateTableSqlTemplate
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
     *
     * @throws ServiceException
     */
    public function processSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        $tableName = $sql->getTable();

        if (!$this->checkUnsupportedFeatures()) {
            throw new ServiceException(
                "SQLite 'ALTER TABLE' only supports the adding new columns."
            );
        }

        $addColumns = $this->parseColumns();
        if ($addColumns) {
            $this->statements[] = "ALTER TABLE {$tableName} ".
                "ADD COLUMN {$addColumns}";
        }
        return implode(';', $this->statements);
    }

    /**
     * Parse column list for SQL create statement
     *
     * @return string
     */
    protected function parseColumns()
    {
        $parts = [];
        foreach ($this->sql->getColumns() as $column) {
            $parts[] = $this->parseColumn($column);
        }
        $tableName = $this->sql->getTable();
        return implode(
            "; ALTER TABLE {$tableName} ADD COLUMN ",
            $parts
        );
    }

    /**
     * Check alter table support
     *
     * @see http://sqlite.org/lang_altertable.html
     */
    private function checkUnsupportedFeatures()
    {
        $addCons = $this->sql->getConstraints();
        $droppedCons = $this->sql->getDroppedConstraints();
        $droppedColumns = $this->sql->getDroppedColumns();
        $changedColumns = $this->sql->getChangedColumns();
        return empty($addCons) && empty($changedColumns)
        && empty($droppedColumns) && empty($droppedCons);
    }
}
