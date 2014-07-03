<?php

/**
 * Update SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql;

use Slick\Database\Sql\SetDataMethods;

/**
 * Update SQL statement
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Update extends AbstractSql implements SqlInterface
{

    /**
     * Import set data methods
     */
    use SetDataMethods {
        SetDataMethods::getParameters as dataParameters;
    }

    /**
     * Use where clause constructor methods
     */
    use WhereMethods {
        WhereMethods::getParameters as whereParameters;
    }

    /**
     * Creates the sql with the table name and fields
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->_table = $tableName;
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
     * Returns the parameters entered in set data
     *
     * @return array
     */
    public function getParameters()
    {
        return array_merge($this->dataParameters(), $this->whereParameters());
    }
}
