<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\Dialect\Standard\SelectSqlTemplate as StandardTpl;

/**
 * Select SQL template
 *
 * @package Slick\Database\Sql\Dialect\Mysql
 */
class SelectSqlTemplate extends StandardTpl
{

    /**
     * Set limit clause when using offset
     *
     * @return SelectSqlTemplate
     */
    protected function setLimitWithOffset()
    {
        $this->setSimpleLimit();
        $this->statement .= " OFFSET {$this->sql->getOffset()}";
        return $this;
    }

    /**
     * Set limit clause for simple limits
     *
     * @return SelectSqlTemplate
     */
    protected function setSimpleLimit()
    {
        $this->statement .= " LIMIT {$this->sql->getLimit()}";
        return $this;
    }
}
