<?php

/**
 * Create
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\SQLite;

use Slick\Database\Query\Sql\Dialect\Standard,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
    Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Database\Query\Ddl\Utility\Column;

/**
 * Create
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Create extends Standard\Create
{

    /**
     * @read
     * @var string The Create Table template SQL
     */
    protected $_template = <<<EOS
CREATE TABLE IF NOT EXISTS `<tableName>` (
<definition>
)<indexes>
EOS;

    /**
     * Returns the SQL query string for current Select SQL Object
     * 
     * @return String The SQL query string
     */
    public function getStatement()
    {
        return trim(
            str_replace(
                array('<tableName>', '<definition>', '<indexes>'),
                array(
                    $this->_sql->getTableName(),
                    $this->getDefinitions(),
                    $this->_getIndexes()
                ),
                $this->_template
            )
        );
    }

    /**
     * Returns all columns, index and constraints definitions
     * 
     * @return string SQL for columns, indexes and constraints
     */
    public function getDefinitions()
    {
        $sql = '';
        $sql .= $this->_getColumns();
        $sql .= $this->_getConstraints();
        return $sql;
    }

    /**
     * Generates the columns definitions for create table statement
     * 
     * @return string
     */
    protected function _getColumns()
    {
        $columns = $this->_sql->getColumns();
        $items = array();
        foreach ($columns as $column) {
            $items[] = "{$this->_tab}" . $this->_getColumnDef($column);
        }

        return implode(",\n", $items);
    }

    /**
     * Generate a column definition SQL
     * 
     * @param  Column $column Column object
     * 
     * @return string
     */
    protected function _getColumnDef(Column $column)
    {
        $str  = "`{$column->name}` ";
        $str .= $this->_getColumnType($column);

        if ($column->isNotNull()) {
            $str .= ' NOT NULL';
        } 
        if ($column->isPrimaryKey()) {
            $str .= ' PRIMARY KEY';
        }
        
        if (strlen($column->default) > 0) {
            $str .= " DEFAULT '{$column->default}'";
        }

        if ($column->isAutoIncrement()) {
            $str  = "`{$column->name}` ";
            $str.= 'INTEGER PRIMARY KEY AUTOINCREMENT';
        }

        return $str;
    }

    /**
     * Generate Index definitions for create table statement
     *
     * @param string $pre Index prefix
     *
     * @return string
     */
    protected function _getIndexes($pre = '')
    {
        $indexes = $this->_sql->getIndexes();
        $values = array();
        $tableName = $this->_sql->getTableName();
        foreach ($indexes as $index) {
            $storage = null;
            $columns = array();

            foreach ($index->getIndexColumns() as $colName) {
                $columns[] = "`{$colName}` ASC";
            }
            $columns = implode(', ', $columns);
            $prefix = '';
            if ($index->type == Index::UNIQUE) {
                $prefix = 'UNIQUE ';
            }
            $values[] = "CREATE {$prefix}INDEX `{$index->name}`{$storage} ".
                "ON {$tableName} ({$columns})";
            
        }
        if (sizeof($values) > 0)
            return ";\n". implode(";\n", $values).';';
        return null;
    }
}