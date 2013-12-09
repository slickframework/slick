<?php

/**
 * SQLite
 * 
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect;

/**
 * SQLite dialect for database queries
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLite extends Standard
{

    /**
     * Parses a Select SQL object into its string query
     * 
     * @return string The SQL Select query statement string
     */
    public function select()
    {
        $dialect = new SQLite\Select(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

    /**
     * Parses a Create SQL 
     * @return [type] [description]
     */
    public function create()
    {
        $dialect = new SQLite\Create(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

    /**
     * Parses a Alter SQL 
     * @return [type] [description]
     */
    public function alter()
    {
        $dialect = new SQLite\Alter(array('sql' => $this->_sql));
        return $dialect->getStatement();
    }

}