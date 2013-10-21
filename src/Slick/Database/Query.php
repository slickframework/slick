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

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Query class is what writes the vendor-specific database code.
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Query extends Base implements Query\QueryInterface
{

    /**
     * @readwrite
     * @var \Slick\Database\Connector The database connector
     */
    protected $_connector;

    /**
     * @read
     * @var string The table(s) to include in queries.
     */
    protected $_from;

    /**
     * @read
     * @var array The field(s) to include in queries.
     */
    protected $_fields = array();

    /**
     * @read
     * @var integer The limit clause in queries.
     */
    protected $_limit;

    /**
     * @read
     * @var integer The select offset for limited rows retreive.
     */
    protected $_offset;

    /**
     * @read
     * @var string The order clause to include in queries.
     */
    protected $_order;

    /**
     * @read
     * @var string The order direction.
     */
    protected $_direction;

    /**
     * @read
     * @var array The joins for multi table select queries.
     */
    protected $_join = array();

    /**
     * @read
     * @var array The query where clause(s)
     */
    protected $_where = array();

    
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
    public function join($join, $on, $fields = array(), $type = null)
    {
        if (empty($join)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'join' value"
            );
        }

        if (empty($on)) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'on' clause"
            );
        }

        $this->_fields += array($join => $fields);
        $this->_join[] = trim("{$type} JOIN {$join} ON {$on}");

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

    /**
     * Quote the input data according to how MySQL will except it.
     *
     * @param mixed $value The imput value to quote.
     * 
     * @return string Quoted input parameter.
     */
    protected function _quote($value, $join = false)
    {
        if (is_string($value)) {
            $escaped = $this->_connector->escape($value);
            return "'{$escaped}'";
        }

        if (is_array($value)) {
            $buffer = array();
            foreach ($value as $i) {
                array_push($buffer, $this->_quote($i));
            }

            if ($join)
            $buffer = join(', ', $buffer);
            return $buffer;
        }

        if (is_null($value)) {
            return "NULL";
        }

        if (is_bool($value)) {
            return (int) $value;
        }

        return $this->connector->escape($value);
    }

    /**
     * Login isolation for all *where methods.
     *
     * @param array $arguments The arguments provided in *where method.
     * 
     * @return array A list of escaped arguments and values for where clause.
     */
    protected function _whereArguments($arguments)
    {
        if (sizeof($arguments) < 1) {
            throw new Exception\InvalidArgumentException(
                "Invalid argument. Enter a valid 'where' value"
            );
        }

        $useJoin = preg_match('/(in \(\?\)|between \(\?\)|not in \(\?\))/i', $arguments[0]);
        $arguments[0] = preg_replace('#\?#', '%s', $arguments[0]);
        $slicedArguments = array_slice($arguments, 1, null, true);

        foreach ($slicedArguments as $i => $parameter) {
            $args = $this->_quote($arguments[$i], (boolean) $useJoin);
            if (is_array($args)) {
                unset($arguments[$i]);
                $arguments = array_merge($arguments, $args);
            } else {
                $arguments[$i] = $args;
            }
        }

        return $arguments;
    }

    /**
     * Builds a SELECT query based on the properties.
     *
     * @return string The SELECT query statement.
     */
    protected function _buildSelect() 
    {
        $fields = array();
        $where = $order = $limit = $join = '';
        $template = "SELECT %s FROM %s %s %s %s %s";

        foreach ($this->fields as $table => $_fields) {
            foreach ($_fields as $field => $alias) {
                if (is_string($field)) {
                    $fields[] = "{$field} AS {$alias}";
                } else {
                    $fields[] = $alias;
                }
            }
        }

        $fields = join(', ', $fields);

        $_join = $this->join;
        if (!empty($_join)) {
            $join = join(' ', $_join);
        }

        $where = $this->_getWhereClause();

        $_order = $this->order;
        if (!empty($_order)) {
            $_direction = $this->direction;
            $order = "ORDER BY {$_order} {$_direction}";
        }

        $_limit = $this->limit;
        if (!empty($_limit)) {
            $_offset = $this->offset;
            if ($_offset) {
                $limit = "LIMIT {$_offset}, {$_limit}";
            } else {
                $limit = "LIMIT {$_limit}";
            }
        }
        $sql = sprintf(
            $template,
            $fields,
            $this->from,
            $join,
            $where,
            $order,
            $limit
        );
        return str_replace('  ', ' ', $sql);
    }

    /**
     * Returns the current where clause string.
     *
     * @return string The SQL WHERE clause for current query.
     */
    protected function _getWhereClause()
    {
        $where = '';
        $_where = $this->where;
        if (!empty($_where)) {
            $seq = array();
            foreach ($_where as $w) {
                $seq[] = $w['op'];
                $seq[] = $w['clause'];
            }
            array_shift($seq);
            $joined = join(' ', $seq);
            $where = "WHERE {$joined}";
        }
        return $where;
    }

    /**
     * Cretates an INSERT query for the given data.
     *
     * @param array|Object $data The data to insert.
     * 
     * @return string The INSERT query statement for given data.
     */
    protected function _buildInsert($data)
    {
        $fields = array();
        $value = array();
        $template = "INSERT INTO %s (`%s`) VALUES (%s)";

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = $this->_quote($value);
        }

        $fields = join("`, `", $fields);
        $values = join(', ', $values);

        return sprintf($template, $this->from, $fields, $values);
    }

    /**
     * Creates an UPDATE query for the given data.
     *
     * @param array|Object $data The data to update.
     * 
     * @return string The UPDATE query statement for given data.
     */
    protected function _buildUpdate($data)
    {
        $parts = array();
        $where = $limit = '';
        $template = "UPDATE %s SET %s %s %s";

        foreach ($data as $field => $value) {
            $parts[] = "`{$field}` = " . $this->_quote($value);
        }

        $parts = join(", ", $parts);

        $where = $this->_getWhereClause();

        $_limit = $this->limit;
        if (!empty($_limit)) {
            $limit = "LIMIT {$_limit}";
        }

        return sprintf($template, $this->from, $parts, $where, $limit);
    }

    /**
     * Creates the DELETE query.
     *
     * @return string The DELETE query statement.
     */
    protected function _buildDelete()
    {
        $where = $limit = '';
        $template = "DELETE FROM %s %s %s";

        $where = $this->_getWhereClause();

        $_limit = $this->limit;
        if (!empty($_limit)) {
            $limit = "LIMIT {$_limit}";
        }

        return sprintf($template, $this->from, $where, $limit);
    }

}
