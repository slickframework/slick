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
    Slick\Database\Query\Ddl\Utility\ElementList;

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
        //die("\n");
        return $columns;
    }

    public function getLines()
    {
        if (is_array($this->_lines)) {
            return $this->_lines;
        }

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
        return $this->_lines;
    }

    protected function _parseColumn($line)
    {
        $line = trim($line);
        $regExp = '/"(?P<name>[a-z_]+)"\s(?P<type>[a-z]+)(\s(?P<properties>.*)|)/i';
        
        if (!preg_match($regExp, $line, $matches)) {
            return false;
        }

        $column = new Column();
        $column->setName($matches['name']);
        //print "\n{$column}";
        return $column;
    }
}