<?php

/**
 * Abstract SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\SqlInterface;

/**
 * Abstract SQL Dialect
 *
 * @package   Slick\Database\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDialect implements DialectInterface
{

    /**
     * @var SqlInterface
     */
    protected $_sql;

    /**
     * Sets the SQL object to be processed
     *
     * @param SqlInterface $sql
     *
     * @return AbstractDialect
     */
    public function setSql(SqlInterface $sql)
    {
        $this->_sql = $sql;
        return $this;
    }

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    abstract public function getSqlStatement();
}
