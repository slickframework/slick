<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 03/08/14
 * Time: 02:43
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\Dialect\Standard\SelectSqlTemplate as StandardTpl;

class SelectSqlTemplate extends StandardTpl
{

    /**
     * Set limit clause when using offset
     *
     * @return SelectSqlTemplate
     */
    protected function _setLimitWithOffset()
    {
        $this->_setSimpleLimit();
        $this->_statement .= " OFFSET {$this->_sql->getOffset()}";
        return $this;
    }

    /**
     * Set limit clause for simple limits
     *
     * @return SelectSqlTemplate
     */
    protected function _setSimpleLimit()
    {
        $this->_statement .= " LIMIT {$this->_sql->getLimit()}";
        return $this;
    }
} 