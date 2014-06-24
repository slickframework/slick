<?php

/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 6/23/14
 * Time: 9:36 PM
 */

namespace Slick\Database\Sql;


class Select extends AbstractSql implements SqlInterface
{

    protected $_fields;

    protected $_joins = [];

    protected $_limit = 100;

    protected $_offset = 0;

    protected $_table;

    protected $_order;

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
        // TODO: Implement getQueryString() method.
    }

    public function orderBy($orderBy)
    {
        // TODO: Implement orderBy() method.
    }

    public function limit($limit, $offset = 0)
    {
        // TODO: Implement limit() method.
    }
}