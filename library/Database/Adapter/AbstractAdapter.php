<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

use PDO;
use Psr\Log\LoggerInterface;
use Slick\Common\Base;
use Slick\Common\Log;
use Slick\Database\Exception\InvalidArgumentException;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\RecordList;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;

/**
 * Abstract database adapter
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property PDO             $handler
 * @property int             $fetchMode
 * @property string          $connectionName
 * @property bool            $autoConnect
 * @property string          $dialect
 * @property LoggerInterface $logger PSR-3 Logger
 *
 * @property-read int  $affectedRows
 * @property-read bool $connected
 *
 *
 * @method bool isAutoConnect()
 */
abstract class AbstractAdapter extends Base implements AdapterInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $connectionName = 'unknown connection name';

    /**
     * @readwrite
     * @var PDO
     */
    protected $handler;

    /**
     * @read
     * @var int
     */
    protected $affectedRows;

    /**
     * @readwrite
     * @var int The PDO fetch mode to use
     */
    protected $fetchMode = PDO::FETCH_NAMED;

    /**
     * @read
     * @var bool A flag for the service connection state
     */
    protected $connected = false;

    /**
     * @readwrite
     * @var bool
     */
    protected $autoConnect = true;

    /**
     * @write
     * @var string
     */
    protected $dialect = Dialect::STANDARD;

    /**
     * @write
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @write
     * @var string
     */
    protected $handleClassName = '\PDO';

    /**
     * Auto connects if the auto connect flag is set to true
     *
     * @param array|object $options The properties for the object
     *                              being constructed.
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        if ($this->autoConnect) {
            $this->connect();
        }
    }

    /**
     * Disconnects from the database service
     *
     * @return AbstractAdapter The current adapter to chain method calls
     */
    public function disconnect()
    {
        $this->handler = null;
        $this->connected = false;
        return $this;
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
        $this->checkConnection();
        $result = $this->runQuery($this->getSql($sql), $parameters);

        return new RecordList(['data' => $result]);
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
        $this->checkConnection();
        $this->runQuery($this->getSql($sql), $parameters);

        return $this->affectedRows;
    }

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last inserted ID value.
     */
    public function getLastInsertId()
    {
        $this->checkConnection();
        return (int) $this->handler->lastInsertId();
    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * Returns the last error occurred
     *
     * @return string The last error of occur.
     */
    public function getLastError()
    {
        $this->checkConnection();
        $errorInfo = $this->handler->errorInfo();
        return $errorInfo[2];
    }

    /**
     * Sets adapter handler
     *
     * @param PDO $handler A PDO object or other object extended from PDO
     *
     * @return AbstractAdapter
     */
    public function setHandler(PDO $handler)
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * Returns the database specific handler used by this adapter
     *
     * This can be the PDO or Mysqli objects for example.
     *
     * @return PDO
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Returns the dialect used in SQL language
     *
     * @return string
     */
    public function getDialect()
    {
        return $this->dialect;
    }

    /**
     * Check whenever the handle is valid and connected
     *
     * @return bool
     */
    public function isConnected()
    {
        $isValidService = $this->handler instanceof PDO;
        return $this->connected && $isValidService;
    }

    /**
     * Handles the request to initialize an already initialized adapter
     *
     * @return AbstractAdapter
     */
    public function initialize()
    {
        return $this;
    }

    /**
     * Returns the logger for this query
     *
     * @return \Monolog\Logger|LoggerInterface
     */
    public function getLogger()
    {
        if (is_null($this->logger)) {
            $this->logger = Log::logger('Database');
        }
        return $this->logger;
    }

    /**
     * Check if the service is valid and its connected
     *
     * @throws \Slick\Database\Exception\ServiceException
     */
    protected function checkConnection()
    {
        if (!$this->isConnected()) {
            throw new ServiceException(
                "Not connected to a valid database service."
            );
        }
    }

    /**
     * Extracts and validates SqlInterface object returning the query string
     *
     * @param string|SqlInterface $sql A string containing the SQL query
     *  to perform ot the equivalent SqlInterface object
     *
     * @return string The query ready to executed
     *
     * @throws \Slick\Database\Exception\InvalidArgumentException if the
     *  sql provided id not a string or does not implements the
     *  Slick\Database\Sql\SqlInterface
     */
    protected function getSql($sql)
    {
        if (is_string($sql)) {
            return $sql;
        }

        if (!($sql instanceof SqlInterface)) {
            throw new InvalidArgumentException(
                "The SQL provided is not a string or does not implements".
                " the Slick\\Database\\Sql\\SqlInterface interface."
            );
        }

        return $sql->getQueryString();
    }

    /**
     * Executes the the provided query
     *
     * @param string $query      A string containing the SQL query to perform
     * @param array  $parameters An array of values with as many elements
     *                           as there are bound parameters in the SQL
     *                           statement being executed
     *
     * @return array An array containing all of the remaining rows in the
     * result set. The array represents each row as either an array of column
     * values or an object with properties corresponding to each column name.
     */
    protected function runQuery($query, $parameters = [])
    {
        try {
            $statement = $this->handler->prepare($query);
            $start = microtime(true);
            $statement->execute($parameters);
            $end = microtime(true);
            $time = $end - $start;
            $this->affectedRows = $statement->rowCount();
            $result = $statement->fetchAll($this->fetchMode);
            $this->getLogger()->info(
                "Query ({$this->connectionName}): Query with results",
                [
                    'query' => $query,
                    'params' => $parameters,
                    'time' => number_format($time, 3),
                    'affected' => $this->affectedRows
                ]
            );
        } catch (\PDOException $exp) {
            throw new SqlQueryException(
                "An error occurred when querying the database service.".
                "SQL: {$query} ".
                "Error: {$exp->getMessage()} ".
                "Database error: {$this->getLastError()}"
            );
        }
        return $result;
    }
}