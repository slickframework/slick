<?php

/**
 * Database mysql query 
 * 
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

use Slick\Database,
    Slick\Database\Exception;

/**
 * Mysql query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends Database\Query
{

    /**
     * Handy method to add a 'LEFT' join to the query
     * 
     * @param string $join     The table to join.
     * @param string $onClause The join condition clause
     * @param array  $fields   The list of fields to add to select.
     * 
     * @return \Slick\Database\Query\Mysql Sefl instance for method
     *   chaining calls.
     */
    public function leftJoin($join, $onClause, $fields = array())
    {
        return $this->join($join, $onClause, $fields, 'LEFT');
    }

    /**
     * Returns a variable number of rows based on the select query performed.
     *
     * @return array A list of rows.
     */
    public function all()
    {
        $sql = $this->_buildSelect();

        $result = $this->_connector->execute($sql);

        if ($result === false) {
            throw new Exception\InvalidSqlException(
                "Error when trying to execute query",
                $this->_connector->lastError,
                $sql
            );
        }

        $rows = array();
        $fields = $result->fetch_fields();
        
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $data = array();
            foreach ($fields as $key => $field) {
                $data[$field->table][$field->name] = $row[$key];
            }
            $rows[] = $data;
        }

        return $rows;
    }

    /**
     * Adds the where clause replacing the ? for the given arguments quoted.
     *
     * @param string $clause   Where clause string.
     * @param mixed  $param1   The values to quote and add.
     * @param mixed  $param... The values...
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function orWhere()
    {
        $arguments = func_get_args();

        $this->_where[] = array(
            'clause' => call_user_func_array(
                'sprintf',
                $this->_whereArguments($arguments)
            ),
            'op' => ' OR '
        );

        return $this;
    }

    /**
     * This is an alias to the where method.
     *
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function andWhere()
    {
        return call_user_func_array(array($this, 'where'), func_get_args());
    }
}
