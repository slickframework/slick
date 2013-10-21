<?php

/**
 * Database query interface
 * 
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

/**
 * Database query interface
 * 
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface QueryInterface
{

    /**
     * From clause for queries.
     *
     * @param string $from   The table name for the query.
     * @param array  $fields A list of fields to select. Defaults to all.
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function from($from, $fields = array('*'));

    /**
     * Adds the join tables, conditions and fields to the query.
     *
     * @param string $join   The table to join.
     * @param string $on     The join condition clause
     * @param array  $fields The list of fields to add to select.
     * @param string $type   One of 'LEFT', 'INNER', 'OUT'. Defaults to 'LEFT'
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function join($join, $on, $fields = array(), $type = 'LEFT');

    /**
     * Adds the limit and page to the query.
     *
     * @param integer $limit The number of rows to retrieve.
     * @param integer $page  The page for offset calculation.
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function limit($limit, $page = 1);

    /**
     * Adds the order clause to the selected query.
     *
     * @param string $order     The order field name(s).
     * @param string $direction The order direction: ASC, DESC;
     *   Defaults to ASC.
     *   
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function order($order, $direction = 'ASC');

    /**
     * Adds the where clause replacing the ? for the given arguments quoted.
     *
     * @param string $clause  Where clause string.
     * @param mixed $param1   The values to quote and add.
     * @param mixed $param... The values...
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function where();

    /**
     * Saves the provided data. If where isn't defined it will do an insert
     * otherwise it will do an update.
     *
     * @param array|Object $data The data to update.
     * 
     * @return integer The last inserted id for new records or 0 for updates.
     */
    public function save($data);

    /**
     * Deletes records for current where statement.
     *
     * @return integer The total rows affected by delete operation.
     */
    public function delete();

    /**
     * Returns the first row of a table.
     *
     * @return array The first row data.
     */
    public function first();

    /**
     * Count the number of rows for the current where clause.
     *
     * @return integer The number of rows.
     */
    public function count();

    /**
     * Returns a variable number of rows based on the select query performed.
     *
     * @return array A list of rows.
     */
    public function all();
}