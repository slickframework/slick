<?php

/**
 * Database query builder
 * 
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Query builder has methods to construct query strings
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class QueryBuilder extends Base
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

        $useJoin = preg_match(
            '/(in \(\?\)|between \(\?\)|not in \(\?\))/i',
            $arguments[0]
        );
        $arguments[0] = preg_replace('#\?#', '%s', $arguments[0]);
        $slicedArguments = array_slice($arguments, 1, null, true);

        $keys = array_keys($slicedArguments);
        foreach ($keys as $i) {
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

        foreach ($this->fields as $_fields) {
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