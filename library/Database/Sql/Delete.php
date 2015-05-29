<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Delete SQL statement
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Delete extends AbstractExecutionOnlySql implements
    ConditionsAwareInterface
{

    /**
     * Use where clause related methods
     */
    use WhereMethods;

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }

}