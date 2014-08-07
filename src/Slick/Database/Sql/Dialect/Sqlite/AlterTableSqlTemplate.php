<?php

/**
 * AlterTable SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Database\Sql\Ddl\AlterTable;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;

/**
 * AlterTable SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
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
     * @throws \Slick\Database\Exception\ServiceException
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        $tableName = $sql->getTable();

        $ok = $this->_checkUnsupportedFeatures();
        if (!$ok) {
            throw new ServiceException(
                "SQLite 'ALTER TABLE' only supports the adding new columns."
            );
        }

        $addColumns = $this->_parseColumns();
        if ($addColumns) {
            $this->_statements[] = "ALTER TABLE {$tableName} " .
                "ADD COLUMN {$addColumns}";
        }

        return implode(';', $this->_statements);
    }

    /**
     * Parse column list for SQL create statement
     *
     * @return string
     */
    protected function _parseColumns()
    {
        $parts = [];
        foreach ($this->_sql->getColumns() as $column) {
            $parts[] = $this->_parseColumn($column);
        }
        $tableName = $this->_sql->getTable();
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
    private function _checkUnsupportedFeatures()
    {
        $addCons = $this->_sql->getConstraints();
        $droppedCons = $this->_sql->getDroppedConstraints();
        $droppedColumns = $this->_sql->getDroppedColumns();
        $changedColumns = $this->_sql->getChangedColumns();

        return empty($addCons) && empty($changedColumns)
            && empty($droppedColumns) && empty($droppedCons);
    }
}
