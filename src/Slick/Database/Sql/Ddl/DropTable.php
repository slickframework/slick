<?php

/**
 * Drop Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\AbstractSql;
use Slick\Database\Sql\SqlInterface;

/**
 * Drop Table SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTable extends AbstractSql implements SqlInterface
{

    /**
     * Creates the sql with the table name
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
}
