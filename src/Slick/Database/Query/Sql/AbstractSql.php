<?php

/**
 * AbstractSql
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

use Slick\Common\Base,
    Slick\Database\Query\QueryInterface,
    Slick\Database\Query\AbstractQuery;

/**
 * AbstractSql is a base implementation for SqlInterface
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSql extends Base implements SqlInterface
{
    /**
     * @readwrite
     * @var QueryInterface
     */
    protected $_query = null;

    /**
     * @readwrite
     * @var array a list of parameters for this query
     */
    protected $_params = array();

    /**
     * @readwrite
     * @var string The table name that will be used in this query
     */
    protected $_tableName;

    /**
     * @readwrite
     * @var array The list of fields used in this query
     */
    protected $_fields = array('*');

    /**
     * @readwrite
     * @var \Slick\Database\Query\Sql\Conditions
     */
    protected $_conditions;

    /**
     * Creates a new SQL statement
     *
     * @param string                               $tableName The database
     *  table for this statment
     * @param array                                $fields    The list of
     *  fields to use in query
     * @param QueryInterface $query     The query object
     *  that gives this statement a context
     */
    public function __construct(
        $tableName, $fields = array('*'), QueryInterface $query = null)
    {
        $options = array(
            'tableName' => $tableName,
            'fields' => $fields,
            'query' => $query
        );

        parent::__construct($options);

        $this->_conditions = new Conditions();
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return AbstractSql A self instance for method chain calls.
     */
    public function where($conditions)
    {
        return $this->_where($conditions);
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return AbstractSql A self instance for method chain calls.
     */
    public function andWhere($conditions)
    {
        return $this->where($conditions);
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return AbstractSql A self instance for method chain calls.
     */
    public function orWhere($conditions)
    {
        return $this->_where($conditions, 'OR');
    }

    /**
     * Returns the current query
     *
     * @return AbstractQuery
     */
    public function getQuery()
    {
        return $this->_query;
    }

    protected function _where($conditions, $operation = 'AND')
    {
        foreach ($conditions as $predicate => $param) {
            //param is a list for IN predicate
            if (preg_match('/IN \([0-9a-z\?:]*\)/i', $predicate)
                && is_array($param)
            ) {
                $this->_params[] = implode(', ', $param);

            } else if (is_array($param)) {
                //param has multiple entries
                foreach ($param as $key => $value) {
                    if (preg_match('/:[a-z_]*/i', $key)) {
                        $this->_params[$key] = $value;
                    } else {
                        $this->_params[] = $value;
                    }
                }

            } else {
                $this->_params[] = $param;
            }

            $this->conditions
                ->addPredicate($predicate)
                ->addOperation($operation);
        }

        return $this;
    }
}
