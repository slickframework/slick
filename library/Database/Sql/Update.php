<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Update SQL statement
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Update extends AbstractSql implements ConditionsAwareInterface
{

    /**
     * Use the data assigning methods
     */
    use SetDataMethods {
        SetDataMethods::getParameters as dataParameters;
    }

    /**
     * Use where clause related methods
     */
    use WhereMethods {
        WhereMethods::getParameters as whereParameters;
    }

    /**
     * Executes an SQL or DDL query and returns the number of affected rows
     *
     * @return integer The number of affected rows by executing the
     *  query
     */
    public function execute()
    {
        return $this->getAdapter()->execute($this, $this->getParameters());
    }

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

    /**
     * Returns the parameters entered in set data
     *
     * @return array
     */
    public function getParameters()
    {
        return array_replace(
            $this->dataParameters(),
            $this->whereParameters()
        );
    }
}