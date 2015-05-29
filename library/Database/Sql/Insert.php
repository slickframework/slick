<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Insert SQL statement
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Insert extends AbstractExecutionOnlySql
{

    /**
     * Use the data assigning methods
     */
    use SetDataMethods;

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