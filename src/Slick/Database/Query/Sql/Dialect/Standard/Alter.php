<?php

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\Standard;

use Slick\Utility\ArrayMethods;

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Alter extends Create
{

    /**
     * @read
     * @var string The Create Table template SQL
     */
    protected $_template = <<<EOS
ALTER TABLE `<tableName>`
<definition>
EOS;

    /**
     * @read
     * @var string To use in column definition perfix
     */
    protected $_definitionPrefix = 'ADD COLUMN ';

    /**
     * Returns all columns, index and constraints definitions
     * 
     * @return string SQL for columns, indexes and constraints
     */
    public function getDefinitions()
    {
        $parts = array(
            $this->_getColumns(),
            $this->_getChangedColumns(),
            $this->_getDroppedColumns(),
            $this->_getIndexes(),
            $this->_getDroppedIndexes(),
            $this->_getConstraints(),
            $this->_getDroppedConstraints(),
            $this->getOptions()
        );
        $parts = ArrayMethods::clean($parts);
        return implode(",\n", $parts);
    }

    /**
     * Generate Index definitions for create table statement
     * @return string
     */
    protected function _getIndexes($prefix = '')
    {
        return $this->_tab . trim(ltrim(parent::_getIndexes('ADD '), ','));
    }

    /**
     * Generate constraints for foreign keys for this create table statement
     * @return string
     */
    protected function _getConstraints($pre = '')
    {
        return $this->_tab . trim(ltrim(parent::_getConstraints('ADD '), ','));
    }

    /**
     * Generate the SQL for changed columns
     * @return string
     */
    protected function _getChangedColumns()
    {
        $columns = $this->_sql->getChangedColumns();
        $items = array();
        foreach ($columns as $column) {
            $str = "CHANGE COLUMN `{$column->name}` " .
                $this->_getColumnDef($column);
            $str = trim($str);
            $items[] = "{$this->_tab}{$str}";
        }

        return implode(",\n", $items);
    }

    /**
     * Generate the drop column SQL
     * @return string
     */
    protected function _getDroppedColumns()
    {
        $columns = $this->_sql->getDroppedColumns();
        $items = array();
        foreach ($columns as $column) {
            $str = "DROP COLUMN `{$column->name}` ";
            $str = trim($str);
            $items[] = "{$this->_tab}{$str}";
        }

        return implode(",\n", $items);
    }

    /**
     * Generates the SQL for drop index statement
     * @return string
     */
    protected function _getDroppedIndexes()
    {
        $indexes = $this->_sql->getDroppedIndexes();
        $items = array();
        foreach ($indexes as $index) {
            $items[] = "{$this->_tab}DROP INDEX `{$index->name}`";
        }
        return implode(",\n", $items);
    }

    /**
     * Generates the SQL for drop foreign keys
     * @return string
     */
    protected function _getDroppedConstraints()
    {
        $foreignKeys = $this->_sql->getDroppedForeignKeys();
        $items = array();
        foreach ($foreignKeys as $foreignKey) {
            $items[] = "{$this->_tab}DROP FOREIGN KEY `{$foreignKey->name}`";
        }
        return implode(",\n", $items);
    }
}