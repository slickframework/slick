<?php

/**
 * Select SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

use Slick\Database\Sql\Select\Join;
use Slick\Database\Sql\Dialect\FieldListAwareInterface;

/**
 * Select SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends AbstractSql implements
    SqlInterface,
    FieldListAwareInterface
{

    /**
     * @var string
     */
    private $_alias;

    /**
     * @var string
     */
    private $_order;

    /**
     * @var string|string[]
     */
    private $_fields;

    /**
     * @var Join[]
     */
    private $_joins = [];

    /**
     * @var int
     */
    private $_offset = 0;

    /**
     * @var int
     */
    private $_limit;

    /**
     * @var bool
     */
    private $_distinct = false;

    /**
     * Use where clause constructor methods
     */
    use WhereMethods;

    /**
     * Creates the sql with the table name and fields
     *
     * @param string $tableName
     * @param string $fields
     */
    public function __construct($tableName, $fields = '*')
    {
        $this->_table = $tableName;
        $this->_fields = $fields;
    }

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->_adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }

    /**
     * Returns the fields statement
     *
     * @return string|string[]
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * Return the current list of join objects
     *
     * @return Join[]
     */
    public function getJoins()
    {
        return $this->_joins;
    }

    /**
     * Get select rows limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * Return limit offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Return order clause
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Returns table alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * Checks if the select query uses the DISTINCT keyword
     *
     * @return bool
     */
    public function isDistinct()
    {
        return $this->_distinct;
    }

    /**
     * Enables or disables the DISTINCT keyword in select
     *
     * @param bool $mode
     *
     * @return Select
     */
    public function setDistinct($mode = true)
    {
        $this->_distinct = $mode;
        return $this;
    }

    /**
     * Adds a join table to the select query
     *
     * If fields is null it will not set any field to the select field
     * list. if it is a string it will be passed to the select field list
     * as it is.
     *
     * If you pass a list of field names they will be prefixed
     * with the alias if is set or the table name, to avoid the ambiguous
     * fields name
     *
     * @param string               $table
     * @param string               $on
     * @param string|null|string[] $fields
     * @param string               $alias
     * @param string               $type
     *
     * @return Select
     */
    public function join(
        $table, $on, $fields = ['*'], $alias = null, $type = Join::JOIN_LEFT)
    {
        $join = new Join($table, $on, $fields, $type);
        if (!is_null($alias)) {
            $join->setAlias($alias);
        }
        $this->_joins[] = $join;
        return $this;
    }

    /**
     * Set order by clause
     *
     * @param string $order
     *
     * @return Select
     */
    public function order($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * Sets query limit and offset
     *
     * @param int $rows
     * @param int $offset
     *
     * @return Select
     */
    public function limit($rows, $offset = 0)
    {
        $this->_limit = $rows;
        $this->_offset = $offset;
        return $this;
    }

    /**
     * Retrieve all records matching this select query
     *
     * @return \Slick\Database\RecordList
     */
    public function all()
    {
        return $this->_adapter->query($this, $this->getParameters());
    }

    /**
     * Retrieve first record matching this select query
     *
     * @return mixed
     */
    public function first()
    {
        $sql = clone($this);
        $sql->limit(1);
        $result = $this->_adapter->query($sql, $sql->getParameters());
        return  (count($result) > 0) ? $result[0]: null;
    }

    /**
     * Counts all records matching this select query
     *
     * @return int
     */
    public function count()
    {
        $total = 0;
        $sql = clone($this);
        $sql->_fields = 'COUNT(*) AS total';
        foreach ($sql->getJoins() as $join) {
            $join->setFields(null);
        }
        $result = $this->_adapter->query($sql, $sql->getParameters());
        if (!empty($result)) {
            $total = intval($result[0]['total']);
        }
        return $total;
    }
}