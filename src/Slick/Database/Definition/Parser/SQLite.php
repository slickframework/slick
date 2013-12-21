<?php

/**
 * SQLite
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition\Parser;

use Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Utility\ArrayMethods;

/**
 * SQLite parser for table definition
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLite extends AbstractParser
{
    /**
     * @readwrite
     * @var array List of create query lines
     */
    protected $_lines = null;

    /**
     * Returns the columns on this data definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList A Column list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::Column
     */
    public function getColumns()
    {
        $columns = new ElementList();
        foreach ($this->lines as $line) {
            $column = $this->_parseColumn($line);
            if ($column) {
                $columns->append($column);
            }
        }
        return $columns;
    }

    /**
     * Returns the indexes on this data definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList An Index list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::Index
     */
    public function getIndexes()
    {
        $indexes = new ElementList();
        foreach ($this->_data as $row) {
            if ($row->type == 'index') {
                $index = new Index(array('name' => $row->name));
                $indexes->append($index);

                if (strpos($row->sql, 'UNIQUE') !== false) {
                    $index->setType(Index::UNIQUE);
                }

                if (preg_match('/\((?P<names>.*)\)/i', $row->sql, $matches)) {
                    $fields = str_replace(
                        array(' ASC', ' DESC'),
                        '',
                        $matches['names']
                    );
                    $index->indexColumns = ArrayMethods::clean(
                        explode(',', $fields)
                    );
                }
            }
        }
        return $indexes;
    }

    /**
     * returns the query lines for parsing
     * 
     * @return array The list of lines form the query.
     */
    public function getLines()
    {
        if (!is_array($this->_lines)) {
            $query = '';
            $regExp = '/"[a-z_]+"\s.*(,|\s\))/i';
            $this->_lines = array();
            foreach ($this->_data as $row) {
                if ($row->type == 'table') {
                    $query = $row->sql;
                    break;
                }
            }

            if (preg_match_all($regExp, $query, $matches)) {
                $this->_lines = $matches[0];
            }
        }

        return $this->_lines;
    }

    /**
     * Parses a column out of a provided query line
     * 
     * @param string $line The query line to parse
     * 
     * @return Column|false A column object or false if the string provided is
     *  not a valid column definition query string.
     */
    protected function _parseColumn($line)
    {
        $line = trim($line);
        $regExp = '/"(?P<name>[a-z_]+)"\s(?P<type>[a-z]+)(\s(?P<properties>.*)|)/i';
        $column = false;
        
        if (preg_match($regExp, $line, $matches)) {
           $column = new Column();
            $column->setName($matches['name']);
            $this->_setType($column, $matches['type']);

            if (isset($matches['properties'])) {
                $this->_checkColumnProperties($column, $matches['properties']);
            }
        }

        return $column;
    }

    /**
     * Sets the proper column type
     * 
     * @param Column $column The column to asign the type
     * @param string $type   The string containing the SQLite type
     */
    protected function _setType(Column &$column, $type)
    {
        switch ($type) {
            case 'REAL':
                $column->setType(Column::TYPE_FLOAT);
                break;

            case 'INTEGER':
                $column->setType(Column::TYPE_INTEGER);
                break;

            case 'BLOB':
                $column->setType(Column::TYPE_BLOB);
                break;
            
            case 'TEXT':
            default:
                $column->setType(Column::TYPE_TEXT);
        }
    }

    protected function _checkColumnProperties(Column &$column, $properties)
    {
        if (strpos($properties, 'PRIMARY KEY') !== false) {
            $column->primaryKey = true;
        }

        if (strpos($properties, 'AUTOINCREMENT') !== false) {
            $column->autoIncrement = true;
        }

        if (strpos($properties, 'NOT NULL') !== false) {
            $column->notNull = true;
        }

    }
}