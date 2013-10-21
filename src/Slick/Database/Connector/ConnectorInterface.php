<?php

/**
 * Database connector interface
 * 
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

/**
 * Database connector interface
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ConnectorInterface
{
    /**
     * Connects to database service.
     *
     * @return \Slick\Database\Connector
     *   A self instance for chain method calls.
     */
    public function connect();

    /**
     * Disconnects from database service.
     * 
     * @return \Slick\Database\Connector
     *   A self instance for chain method calls.
     */
    public function disconnect();

    /**
     * Returns a corresponding query instance.
     * 
     * @return \Slick\Database\Query
     */
    public function query();

    /**
     * Executes the provided SQL statement.
     *
     * @param string $sql The SQL statment to execute.
     * 
     * @return mixed The \Slick\Database\Connector::query() result.
     * @see \Slick\Database\Connector::query()
     */
    public function execute($sql);

    /**
     * Escapes the provided value to make it safe for queries.
     *
     * @param string $value The value to escape.
     * 
     * @return string A safe string for queries.
     */
    public function escape($value);

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last insertd ID value.
     */
    public function getLastInsertId();

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows();

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     */
    public function getLastError();
}
