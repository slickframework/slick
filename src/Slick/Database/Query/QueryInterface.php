<?php

/**
 * QueryInterface
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

/**
 * QueryInterface define a database query behavior
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface QueryInterface
{

    /**
     * Creates a prepared statement, ready to receive params from given SQL
     * 
     * @param string $sql The SQL statement to prepare
     * 
     * @return /PDOStatement A prepared PDOStatement object
     * @see  http://www.php.net/manual/en/class.pdostatement.php
     */
    public function prepare($sql);

    /**
     * Executes current query, binding the provided parameters
     * 
     * @param array $params List of parameters to set before execute que query
     * 
     * @return \Slick\Database\RecordList A record list with the query results
     */
    public function execute($params = array());
}