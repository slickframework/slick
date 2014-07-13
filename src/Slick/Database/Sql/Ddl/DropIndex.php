<?php

/**
 * Drop table index SQL statement
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
use Slick\Database\Sql\ExecuteMethods;

/**
 * Drop table index SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropIndex extends AbstractSql implements SqlInterface
{
    /**
     * Use execute methods
     */
    use ExecuteMethods;

    /**
     * @var string
     */
    protected $_name;

    /**
     * Creates the sql with the table name
     *
     * @param $name
     * @param string $tableName
     */
    public function __construct($name, $tableName)
    {
        $this->_name = $name;
        $this->_table = $tableName;
    }

    /**
     * Returns index name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
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
