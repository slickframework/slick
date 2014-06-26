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

/**
 * Select SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends AbstractSql implements SqlInterface
{

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $_table;

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
    private $_limit = 100;

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
     * @return string|\string[]
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return \Slick\Database\Sql\Select\Join[]
     */
    public function getJoins()
    {
        return $this->_joins;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @return string
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
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

    public function first()
    {

    }

    public function all()
    {

    }

    public function count()
    {

    }
}