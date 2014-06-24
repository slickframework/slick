<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 6/23/14
 * Time: 9:39 PM
 */

namespace Slick\Database\Sql;


use Slick\Database\Adapter\AbstractAdapter;

abstract class AbstractSql implements SqlInterface
{

    /**
     * @var AbstractAdapter
     */
    protected $_adapter;

    /**
     * Sets the adapter for this statement
     *
     * @param AbstractAdapter $adapter
     * @return AbstractSql
     */
    public function setAdapter(AbstractAdapter $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }
} 