<?php

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\SQLite;

use Slick\Utility\ArrayMethods;

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
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
<definition>;
EOS;

    /**
     * Returns all columns, index and constraints definitions
     * 
     * @return string SQL for columns, indexes and constraints
     */
    public function getDefinitions()
    {
        $parts = array(
            $this->_getColumns(),
            $this->_getIndexes(),
            $this->_getDroppedIndexes()
        );
        $parts = ArrayMethods::clean($parts);
        return implode(";\n", $parts);
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
            $items[] = "{$this->_tab}ADD COLUMN " .
                $this->_getColumnDef($column);
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
            $items[] = "DROP INDEX `{$index->name}`";
        }
        return implode(";\n", $items);
    }

    /**
     * Generate Index definitions for create table statement
     *
     * @param string $pre Index prefixes
     *
     * @return string
     */
    protected function _getIndexes($pre = '')
    {
        $str = parent::_getIndexes($pre);
        return ltrim(trim($str, ';'));
    }
}