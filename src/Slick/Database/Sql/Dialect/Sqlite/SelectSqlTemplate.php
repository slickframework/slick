<?php

/**
 * Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Database\Sql\Dialect\Standard\SelectSqlTemplate as StandardTpl;

/**
 * Select SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
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
