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
    Slick\Database\Query\Ddl\Utility\ForeignKey,
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

    protected $_regFk = '';

    protected $_frks = array();

    public function __construct($options = array())
    {
        $this->_regFk = '/CONSTRAINT [ `"](?P<name>[a-z-_]+).*\n*\s*FOREIGN ';
        $this->_regFk .= 'KEY \(?[ `"](?P<frk>[a-z-_]+)[ `"]\)?.*\n*\s*';
        $this->_regFk .= 'REFERENCES [ `"](?P<table>[a-z-_]+)[ `"] \([ `"]';
        $this->_regFk .= '(?P<ref>[a-z-_]+)[ `"]\)\n*\s*ON DELETE ';
        $this->_regFk .= '(?P<del>[a-z ]+)\s*\n*\s*ON UPDATE ';
        $this->_regFk .= '(?P<upd>[a-z ]+)/i';
        parent::__construct($options);
    }

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
            if ($row['type'] == 'index') {
                $index = new Index(array('name' => $row['name']));
                $indexes->append($index);

                if (strpos($row['sql'], 'UNIQUE') !== false) {
                    $index->setType(Index::UNIQUE);
                }

                if (preg_match('/\((?P<names>.*)\)/i', $row['sql'], $matches)) {
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
     * Returns the foreign keys on this data definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList A ForeignKey list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::ForeignKey
     */
    public function getForeignKeys()
    {
        $frks = new ElementList();
        $this->getLines();
        foreach ($this->_frks as $line) {
            $frk = $this->_parseForeignKey($line);
            if ($frk) {
                $frks->append($frk);
            }
        }
        return $frks;
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
            $regExp = '/(?P<l>.*),?\n/i';
            $this->_lines = array();
            foreach ($this->_data as $row) {
                if ($row['type'] == 'table') {
                    $query = $row['sql'];
                    break;
                }
            }

            if (preg_match_all($regExp, $query, $matches)) {
                foreach ($matches['l'] as $line) {
                    $line = rtrim(trim($line), ',');
                    if (strpos($line, 'CREATE TABLE') === false) {
                        $this->_lines[] = $line;
                    }
                }
            }

            if (preg_match_all($this->_regFk, $query, $lines)) {
                $this->_frks = $lines[0];
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
        $regExp = '/[`"](?P<name>[a-z-_]+)[`"] (?P<type>[a-z_]+)\(?[0-9]*\)?';
        $regExp .= '\s?(?P<properties>[a-z\s]*)/i';
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

    protected function _parseForeignKey($line)
    {

        $frk = false;

        if (preg_match($this->_regFk, $line, $matches)) {
            $frk = new ForeignKey(
                array(
                    'name' => $matches['name'],
                    'indexColumns' => array($matches['frk'] => $matches['ref']),
                    'referencedTable' => $matches['table'],
                    'onDelete' => $this->_checkFkAction($matches['del']),
                    'onUpdate' => $this->_checkFkAction($matches['upd'])
                )
            );
        }

        return $frk;
    }

    /**
     * Sets the proper column type
     * 
     * @param Column $column The column to asign the type
     * @param string $type   The string containing the SQLite type
     */
    protected function _setType(Column &$column, $type)
    {
        $type = str_replace(
            array('BIG', 'TINY', 'SMALL', 'MEDIUM', 'LONG'),
            '',
            $type
        );
        switch ($type) {
            case 'REAL':
                $column->setType(Column::TYPE_FLOAT);
                break;

            case 'INTEGER':
            case 'INT':
                $column->setType(Column::TYPE_INTEGER);
                break;

            case 'BLOB':
                $column->setType(Column::TYPE_BLOB);
                break;

            case 'VARCHAR':
                $column->setType(Column::TYPE_VARCHAR);
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

    protected function _checkFkAction($str)
    {
        switch (trim($str)) {
            case 'SET NULL':
                $action = ForeignKey::SET_NULL;
                break;

            case 'RESTRICT':
                $action = ForeignKey::RESTRICT;
                break;

            case 'CASCADE':
                $action = ForeignKey::CASCADE;
                break;
            
            case 'NO ACTION':
            default:
                $action = ForeignKey::NO_ACTION;
        }
        return $action;
    }
}