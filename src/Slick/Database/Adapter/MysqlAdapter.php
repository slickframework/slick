<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 6/15/14
 * Time: 2:14 AM
 */

namespace Slick\Database\Adapter;


use Slick\Common\Base;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\RecordList;
use Slick\Database\Sql\SqlInterface;

class MysqlAdapter extends Base implements AdapterInterface
{

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function connect()
    {
        // TODO: Implement connect() method.
    }

    /**
     * Disconnects from the database service
     *
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }

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
    public function query($sql, $parameters = [])
    {
        // TODO: Implement query() method.
    }

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
    public function execute($sql, $parameters = [])
    {
        // TODO: Implement execute() method.
    }

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last inserted ID value.
     */
    public function getLastInsertId()
    {
        // TODO: Implement getLastInsertId() method.
    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows()
    {
        // TODO: Implement getAffectedRows() method.
    }

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     */
    public function getLastError()
    {
        // TODO: Implement getLastError() method.
    }

    /**
     * Returns the database specific handler used by this adapter
     *
     * This can be the PDO or Mysqli objects for example.
     *
     * @return mixed
     */
    public function getHandler()
    {
        // TODO: Implement getHandler() method.
    }
}