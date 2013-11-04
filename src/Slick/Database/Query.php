<?php

/**
 * Query
 * 
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
namespace Slick\Database;

use Slick\Database\Query\QueryBuilder,
    Slick\Database\Exception;

/**
 * Query class is what writes the vendor-specific database code.
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Query extends QueryBuilder implements Query\QueryInterface
{

    /**
     * Returns the first row of a table.
     *
     * @return array The first row data.
     */
    public function first()
    {
        $limit = $this->_limit;
        $offset = $this->_offset;

        $this->limit(1);

        $all = $this->all();

        $first = reset($all);

        if ($limit) {
            $this->_limit = $limit;
        }

        if ($offset) {
            $this->_offset = $offset;
        }

        return $first;
    }

    /**
     * Count the number of rows for the current where clause.
     *
     * @return integer The number of rows.
     */
    public function count()
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $fields = $this->fields;
        $order = $this->order;

        $this->_fields = array($this->from => array('COUNT(1)' => 'rows'));

        $this->limit(1);
        $this->_order = null;
        $row = $this->first();

        if ($limit) {
            $this->_limit = $limit;
        }

        if ($fields) {
            $this->_fields = $fields;
        }

        if ($offset) {
            $this->_offset = $offset;
        }

        if ($order) {
            $this->_order = $order;
        }

        return $row['']['rows'];
    }

    /**
     * Saves the provided data.
     * 
     * If where isn't defined it will do an insert otherwise it will
     * do an update.
     *
     * @param array|Object $data The data to update.
     * 
     * @return integer The last inserted id for new records or 0
     *   for updates.
     */
    public function save($data)
    {
        $isInsert = sizeof($this->_where) == 0;

        if ($isInsert) {
            $sql = $this->_buildInsert($data);
        } else {
            $sql = $this->_buildUpdate($data);
        }

        $result = $this->_connector->execute($sql);

        if ($result === false) {
            throw new Exception\InvalidSqlException(
                "Error when trying to execute query",
                $this->_connector->lastError,
                $sql
            );
        }

        if ($isInsert) {
            return $this->_connector->lastInsertId;
        }

        return 0;
    }

    /**
     * Deletes records for current where statement.
     *
     * @return integer The total rows affected by delete operation.
     */
    public function delete()
    {
        $sql = $this->_buildDelete();

        $result = $this->_connector->execute($sql);

        if ($result === false) {
            throw new Exception\InvalidSqlException(
                "Error when trying to execute query",
                $this->_connector->lastError,
                $sql
            );
        }

        return $this->_connector->affectedRows;

    }

    /**
     * From clause for queries.
     *
     * @param string $from   The table name for the query.
     * @param array  $fields A list of fields to select. Defaults to all.
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function from($from, $fields = array('*'))
    {
        if (empty($from)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'from' value"
            );
        }

        $this->_from = $from;

        if ($fields) {
            $this->_fields[$from] = $fields;
        }

        return $this;
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
    public function where()
    {
        $arguments = func_get_args();

        $this->_where[] = array(
            'clause' => call_user_func_array(
                'sprintf',
                $this->_whereArguments($arguments)
            ),
            'op' => ' AND '
        );

        return $this;
    }

    /**
     * Adds the join tables, conditions and fields to the query.
     *
     * @param string $join     The table to join.
     * @param string $onClause The join condition clause
     * @param array  $fields   The list of fields to add to select.
     * @param string $type     One of LEFT, INNER, OUT. Defaults to LEFT
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function join($join, $onClause, $fields = array(), $type = null)
    {
        if (empty($join)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'join' value"
            );
        }

        if (empty($onClause)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'on' clause"
            );
        }

        $this->_fields += array($join => $fields);
        $this->_join[] = trim("{$type} JOIN {$join} ON {$onClause}");

        return $this;
    }

    /**
     * Adds the limit and page to the query.
     *
     * @param integer $limit The number of rows to retrieve.
     * @param integer $page  The page for offset calculation.
     * 
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function limit($limit, $page = 1)
    {
        if (empty($limit)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'limit' value"
            );
        }

        $this->_limit = $limit;
        $this->_offset = $limit * ($page - 1);

        return $this;
    }

    /**
     * Adds the order clause to the selected query.
     *
     * @param string $order     The order field name(s).
     * @param string $direction The order direction: ASC, DESC;
     *  Defaults to ASC.
     *  
     * @return \Slick\Database\Query Sefl instance for method chaining calls.
     */
    public function order($order, $direction = 'ASC')
    {
        if (empty($order)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'order' value"
            );
        }

        $this->_order = $order;
        $this->_direction = $direction;

        return $this;
    }   

}
