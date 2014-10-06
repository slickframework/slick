<?php

/**
 * Select
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;
use Slick\Utility\ArrayObject;

/**
 * Select is a representation of a SQL select statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends AbstractSql implements SelectInterface
{

    /**#@+
     * @const string JOIN constant types
     */
    const JOIN_NATURAL             = 'NATURAL';
    const JOIN_NATURAL_LEFT        = 'NATURAL LEFT';
    const JOIN_NATURAL_LEFT_OUTER  = 'NATURAL LEFT OUTER';
    const JOIN_NATURAL_RIGHT       = 'NATURAL RIGHT';
    const JOIN_NATURAL_RIGHT_OUTER = 'NATURAL RIGHT OUTER';
    const JOIN_LEFT_OUTER          = 'LEFT OUTER';
    const JOIN_RIGHT_OUTER         = 'RIGHT OUTER';
    const JOIN_LEFT                = 'LEFT'; // -> The default
    const JOIN_RIGHT               = 'RIGHT';
    const JOIN_INNER               = 'INNER';
    const JOIN_CROSS               = 'CROSS';
    /**#@-*/

    /**
     * @readwrite
     * @var ArrayObject The list of joins for this select
     */
    protected $_joins = null;

    /**
     * @readwrite
     * @var string order by clause
     */
    protected $_orderBy = null;

    /**
     * @readwrite
     * @var string Group by clause
     */
    protected $_groupBy = null;

    /**
     * @readwrite
     * @var integer Limit the number of rows to retrieve
     */
    protected $_limit = 100;

    /**
     * @readwrite
     * @var integer Where to start retrieving rows
     */
    protected $_offset = 0;

    /**
     * @readwrite
     * @var boolean Tells the dialect to prefix the table on field list
     */
    protected $_prefixTableName = true;

    /**
     * Returns a RecordList with all records result for this select.
     * 
     * @return \Slick\DataBase\RecordList
     */
    public function all()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute($this->params);
    }

    /**
     * Retrieves the first row of a given select query
     * 
     * @return object The record or null.
     */
    public function first()
    {
        $limit = $this->_limit;
        $offset = $this->_offset;
        $this->limit(1);
        $result = $this->all();

        $this->_limit  = $limit;
        $this->_offset = $offset;
        return is_bool($result) ? [] : reset($result);
    }

    /**
     * Count the rows for current where conditions
     * 
     * @return integer The total rows for current conditions
     */
    public function count()
    {
        $limit = $this->_limit;
        $offset = $this->_offset;
        $this->limit(0);

        $joins = $this->getJoins();
        $copyJoins = clone($joins);
        
        $keys = array_keys($copyJoins->getArrayCopy());
        foreach ($keys as $key) {
            $joins[$key]['fields'] = array();
        }

        $this->_joins = $joins;

        $fields = $this->_fields;
        $this->_fields = array('COUNT(*) AS totalRows');
        $prefix = $this->_prefixTableName;
        $this->_prefixTableName = false;

        $result = $this->all();

        $this->_prefixTableName = $prefix;
        $this->_fields = $fields;
        $this->_joins = $copyJoins; 
        $this->_limit  = $limit;
        $this->_offset = $offset;
        return (isset($result[0]['totalRows'])) ? $result[0]['totalRows'] : 0;
    }

    /**
     * Lazy method to create and return the joins list
     * 
     * @return ArrayObject The list of joins used.
     */
    public function getJoins()
    {
        if (is_null($this->_joins)) {
            $this->_joins = new ArrayObject();
        }
        return $this->_joins;
    }

    /**
     * Adds a join table to the current select statement
     * 
     * @param string $table  The join table name
     * @param string $clause The condition for the join 
     * @param array  $fields The fields list to retrieve 
     * @param string $type   The join type
     * 
     * @return \Slick\Database\Query\Sql\Select A self instance for method
     *  call chains
     */
    public function join(
        $table, $clause, array $fields = [], $type = self::JOIN_LEFT)
    {
        if (empty($fields)) {
            $fields = array("*");
        }
        $this->getJoins()->append(
            array(
                'table' => $table,
                'onClause' => $clause,
                'fields' => $fields,
                'type' => $type
            )
        );
        return $this;
    }

    /**
     * Sets the order by clause in this SQL Select statement
     * 
     * @param string $order The order by class
     * 
     * @return \Slick\Database\Query\Sql\Select A self instance for method
     *  call chains
     */
    public function orderBy($order)
    {
        $this->_orderBy = $order;
        return $this;
    }

    /**
     * Sets the group by clause on this SQL select statement
     * 
     * @param string $groupBy The group by class
     * 
     * @return \Slick\Database\Query\Sql\Select A self instance for method
     *  call chains
     */
    public function groupBy($groupBy)
    {
        $this->_groupBy = $groupBy;
        return $this;
    }

    /**
     * Sets the limit and the offset for this query
     * 
     * @param integer $total  The total rows to retrieve
     * @param integer $offset The starting point where to count rows
     * 
     *  @return \Slick\Database\Query\Sql\Select A self instance for method
     *  call chains
     */
    public function limit($total, $offset = 0)
    {
        $this->_limit = $total;
        $this->_offset = $offset;
        return $this;
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return Select A self instance for method chain calls.
     */
    public function where($conditions)
    {
        return parent::where($conditions);
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return Select A self instance for method chain calls.
     */
    public function andWhere($conditions)
    {
        return parent::andWhere($conditions);
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return Select A self instance for method chain calls.
     */
    public function orWhere($conditions)
    {
        return parent::orWhere($conditions);
    }
}