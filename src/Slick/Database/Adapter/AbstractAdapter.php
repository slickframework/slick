<?php

/**
 * Abstract database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Adapter;

use PDO;
use Slick\Log\Log;
use Slick\Common\Base;
use Psr\Log\LoggerInterface;
use Slick\Database\RecordList;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\Exception\InvalidArgumentException;

/**
 * Abstract database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property      PDO $handler
 * @property-read int $affectedRows
 * @property      int $fetchMode
 * @property LoggerInterface $logger PSR-3 Logger
 *
 * @method AbstractAdapter setLogger(LoggerInterface $logger) Sets PSR-3 Logger
 */
abstract class AbstractAdapter extends Base implements AdapterInterface
{
    /**
     * @readwrite
     * @var string
     */
    protected $_connectionName = 'unknown connection name';

    /**
     * @readwrite
     * @var PDO
     */
    protected $_handler;

    /**
     * @read
     * @var int
     */
    protected $_affectedRows;

    /**
     * @readwrite
     * @var int The PDO fetch mode to use
     */
    protected $_fetchMode = PDO::FETCH_NAMED;

    /**
     * @read
     * @var bool A flag for the service connection state
     */
    protected $_connected = false;

    /**
     * @readwrite
     * @var bool
     */
    protected $_autoConnect = true;

    /**
     * @write
     * @var string
     */
    protected $_handlerClass = '\PDO';

    /**
     * @write
     * @var string
     */
    protected $_dialect = Dialect::STANDARD;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * Auto connects if the auto connect flag is set to true
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        if ($this->_autoConnect) {
            $this->connect();
        }
    }

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     * @return AdapterInterface The current adapter to chain method calls
     */
    abstract public function connect();

    /**
     * Disconnects from the database service
     *
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function disconnect()
    {
        $this->_handler = null;
        $this->_connected = false;
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
     * @throws \Slick\Database\Exception\InvalidArgumentException if the
     *  sql provided id not a string or does not implements the
     *  Slick\Database\Sql\SqlInterface
     *
     * @throws SqlQueryException If any error occurs while preparing or
     *  executing the SQL query
     *
     * @return RecordList The records that are queried in the SQL
     *  statement provided. Note that this list can be empty.
     */
    public function query($sql, $parameters = [])
    {
        $this->_checkConnection();

        $query = $sql;
        if (is_object($sql)) {
            if (!($sql instanceof SqlInterface)) {
                throw new InvalidArgumentException(
                    "The SQL provided is not a string or does not " .
                    "implements the Slick\Database\Sql\SqlInterface interface."
                );
            }

            $query = $sql->getQueryString();
        }

        try {
            $statement = $this->_handler->prepare($query);
            $start = microtime(true);
            $statement->execute($parameters);
            $end = microtime(true);
            $time = $end - $start;
            $result = $statement->fetchAll($this->_fetchMode);
            $this->getLogger()->info(
                "Query ({$this->connectionName}): {$query}",
                [
                    'query' => $query,
                    'params' => $query,
                    'time' => number_format($time, 3),
                    'affected' => $statement->rowCount()
                ]
            );
        } catch (\PDOException $exp) {
            throw new SqlQueryException(
                "An error occurred when querying the database service." .
                "SQL: {$query} " .
                "Error: {$exp->getMessage()} " .
                "Database error: {$this->getLastError()}"
            );
        }

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
     * @throws \Slick\Database\Exception\InvalidArgumentException if the
     *  sql provided id not a string or does not implements the
     *  Slick\Database\Sql\SqlInterface
     *
     * @throws SqlQueryException If any error occurs while preparing or
     *  executing the SQL query
     *
     * @return integer The number of affected rows by executing the
     *  query
     */
    public function execute($sql, $parameters = [])
    {
        $this->_checkConnection();

        $query = $sql;
        if (is_object($sql)) {
            if (!($sql instanceof SqlInterface)) {
                throw new InvalidArgumentException(
                    "The SQL provided is not a string or does not " .
                    "implements the Slick\Database\Sql\SqlInterface interface."
                );
            }

            $query = $sql->getQueryString();
        }

        try {
            $start = microtime(true);
            $statement = $this->_handler->prepare($query);
            $statement->execute($parameters);
            $end = microtime(true);
            $time = $end -$start;
            $this->_affectedRows = $statement->rowCount();
            $this->getLogger()->info(
                "Query ({$this->connectionName}): {$query}",
                [
                    'query' => $query,
                    'params' => $query,
                    'time' => number_format($time, 3),
                    'affected' => $statement->rowCount()
                ]
            );
        } catch (\PDOException $exp) {
            throw new SqlQueryException(
                "An error occurred when querying the database service." .
                "SQL: {$query} " .
                "Error: {$exp->getMessage()} " .
                "Database error: {$this->getLastError()}"
            );
        }

        return $this->_affectedRows;
    }

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last inserted ID value.
     */
    public function getLastInsertId()
    {
        $this->_checkConnection();
        return $this->_handler->lastInsertId();
    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows()
    {
        return $this->_affectedRows;
    }

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     */
    public function getLastError()
    {
        $this->_checkConnection();
        $errorInfo = $this->_handler->errorInfo();
        return $errorInfo[2];
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
        return $this->_handler;
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
     * Check whenever the handle is valid and connected
     *
     * @return bool
     */
    public function isConnected()
    {
        $isValidService = $this->_handler instanceof PDO;
        return $this->_connected && $isValidService;
    }

    /**
     * Returns the dialect used in SQL language
     *
     * @return string
     */
    public function getDialect()
    {
        return $this->_dialect;
    }

    /**
     * Returns the schema name for this adapter
     *
     * @return string
     */
    abstract public function getSchemaName();

    /**
     * Returns the logger for this query
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (is_null($this->_logger)) {
            $this->_logger = Log::logger('Database');
        }
        return $this->_logger;
    }

    /**
     * Check if the service is valid and its connected
     *
     * @throws \Slick\Database\Exception\ServiceException
     */
    protected function _checkConnection()
    {
        if (!$this->isConnected()) {
            throw new ServiceException(
                "Not connected to a valid database service."
            );
        }
    }
}