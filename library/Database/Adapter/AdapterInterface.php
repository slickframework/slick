<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

use Slick\Database\Exception\ServiceException;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\RecordList;
use Slick\Database\Sql\SqlInterface;

/**
 * Interface for a database adapter
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface AdapterInterface
{

    /**
     * Connects to the database service
     *
     * @return AdapterInterface The current adapter to chain method calls
     *
     *@throws ServiceException If any error occurs while trying to
     *  connect to the database service
     */
    public function connect();

    /**
     * Disconnects from the database service
     *
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function disconnect();

    /**
     * Executes a SQL query and returns a record list
     *
     * @param string|SqlInterface $sql A string containing the SQL query
     *  to perform ot the equivalent SqlInterface object
     * @param array $parameters An array of values with as many elements
     *  as there are bound parameters in the SQL statement being executed
     *
     * @throws SqlQueryException If any error occurs while preparing or
     *  executing the SQL query
     *
     * @return RecordList The records that are queried in the SQL
     *  statement provided. Note that this list can be empty.
     */
    public function query($sql, $parameters = []);

    /**
     * Executes an SQL or DDL query and returns the number of affected rows
     *
     * @param string|SqlInterface $sql A string containing
     *  the SQL query to perform ot the equivalent SqlInterface or
     *  DdlInterface object
     * @param array $parameters
     *
     * @throws SqlQueryException If any error occurs while preparing or
     *  executing the SQL query
     *
     * @return integer The number of affected rows by executing the
     *  query
     */
    public function execute($sql, $parameters = []);

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last inserted ID value.
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

    /**
     * Returns the database specific handler used by this adapter
     *
     * This can be the PDO or Mysqli objects for example.
     *
     * @return mixed
     */
    public function getHandler();

    /**
     * Returns the dialect used in SQL language
     *
     * @return string
     */
    public function getDialect();

    /**
     * Returns the schema name for this adapter
     *
     * @return string
     */
    public function getSchemaName();

}