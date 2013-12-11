<?php

/**
 * Mysql
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition\Parser;

use Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\ElementList;

/**
 * Mysql parser for table definition
 *
 * @package   Slick\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends AbstractParser
{
    /**
     * @read
     * @var array
     */
    protected $_lines = null;

    /**
     * @var array Mysql types
     */
    protected $_types = array(
        Column::TYPE_INTEGER => array('INT', 'TIMESTAMP', 'YEAR'),
        Column::TYPE_TEXT => array('TEXT'),
        Column::TYPE_FLOAT => array('DECIMAL', 'FLOAT', 'DOUBLE', 'NUMERIC'),
        Column::TYPE_VARCHAR => array('VARCHAR'),
        Column::TYPE_BLOB => array('BLOB'),
        Column::TYPE_DATETIME => array('DATETIME', 'DATE'),
        Column::TYPE_TEXT => array(),
    );

    /**
     * @var array Size translations
     */
    protected $_sizes = array(
        Column::SIZE_SMALL => array('TINY', 'SMALL'),
        Column::SIZE_NORMAL => array(''),
        Column::SIZE_MEDIUM => array('MEDIUM'),
        Column::SIZE_BIG => array('BIG', 'LONG'),
    );

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
        foreach ($this->getLines() as $line) {
            $column = $this->_parseColumn($line);
            if ($column) {
                $columns->append($column);
            }
            $this->_checkPrimaryKey($line, $columns);
        }
        return $columns;
    }

    /**
     * Retrive the definition lines from data
     * 
     * @return array
     */
    public function getLines()
    {
        if (!is_array($this->_lines)) {
            $prop = 'Create Table';
            preg_match_all('/(.*\,|.*\))/i', $this->_data[0]->$prop, $matches);

            foreach ($matches[0] as $line) {
                $line = rtrim(trim($line), ',');
                if ($line != ')') {
                    $this->_lines[] = $line;
                }
            }
        }
        return $this->_lines;
    }

    /**
     * Parses a line from result to create a column
     * 
     * @param string $line Column definition line from query
     * 
     * @return Column|false A column created from the definition line or
     *  boolean false if the line isn't a column definition.
     */
    protected function _parseColumn($line)
    {
        $regExp = '/`(?P<name>[a-z_]+)`\s(?P<type>[a-z]+)\(?(?P<length>[0-9]*)\)?(?P<properties>.*)/i';
        if (!preg_match($regExp, $line, $matches)) {
            return false;
        }
        $column = new Column(array('name' => trim($matches['name'])));

        if (strlen($matches['length'])) {
            $column->length = $matches['length'];
        }
        
        preg_match(
            '/(?P<size>(TINY|LONG|MEDIUM|SMALL|BIG)?)(?P<type>[a-z]+)/i',
            $matches['type'],
            $parts
        );

        $column->type = $this->_matchType(trim($parts['type']), $matches['length']);
        $column->size = $this->_matchSize($parts['size']);

        if (strpos(strtoupper($matches['properties']), 'NOT NULL')) {
            $column->notNull = true;
        }

        if (strpos(strtoupper($matches['properties']), 'UNSIGNED')) {
            $column->unsigned = true;
        }

        if (strpos(strtoupper($matches['properties']), 'AUTO_INCREMENT')) {
            $column->autoIncrement = true;
        }

        if (
            preg_match(
                '/DEFAULT\s\'(?P<default>.*)\'/i',
                $matches['properties'],
                $result
            )
        ) {
            $column->default = $result['default'];
        }

        if (
            preg_match(
                '/COMMENT\s\'(?P<comment>.*)\'/i',
                $matches['properties'],
                $result
            )
        ) {
            $column->description = $result['comment'];
        }

        return $column;
    }

    /**
     * Check if line is a primary key index and sets the appropriate flag in
     * the corresponding column.
     * 
     * @param  string      $line    Primary key definition line from query
     * @param  ElementList $columns The current list of columns
     */
    protected function _checkPrimaryKey($line, $columns)
    {
        if (
            preg_match(
                '/PRIMARY KEY \(`(?P<name>[a-z_]+)`\)/i',
                $line,
                $result
            )
        ) {
            foreach ($columns as $col) {
                if ($col->name == $result['name']) {
                    $col->primaryKey = true;
                    break;
                }
            }
        }
    }

    /**
     * Retrieves the column type from provided MySQL type string
     * 
     * @param string  $type MySQL type string
     * @param integer $len  The parser column length
     * 
     * @return integer The correct column type
     */
    protected function _matchType($type, $len = 0)
    {
        if (strtoupper($type) == 'INT' && $len == 1) {
            return Column::TYPE_BOOLEAN;
        }

        foreach ($this->_types as $colType => $values) {
            if (in_array(strtoupper($type), $values)) {
                return $colType;
            }
        }

        return Column::TYPE_TEXT;
    }

    /**
     * Retrieves the column size fot the provided MySql type string
     * 
     * @param string $size MySql type string (size part: TINY, BIG, ...)
     * 
     * @return string The correct column size
     */
    protected function _matchSize($size)
    {
        $return = Column::SIZE_NORMAL;
        foreach ($this->_sizes as $colSize => $values) {
            if (in_array(strtoupper($size), $values)) {
                $return = $colSize;
                break;
            }
        }

        return $return;
    }

}