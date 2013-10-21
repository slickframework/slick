<?php

/**
 * Database Mysql connector
 * 
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

use Slick\Database,
    Slick\Database\Exception;

/**
 * Mysql database connector
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends Database\Connector
{
    /**
     * @readwrite
     * @var \MySQLi Mysql resource connector
     */
    protected $_service;

    /**
     * @readwrite
     * @var string The mysql server host name
     */
    protected $_host;

    /**
     * @readwrite
     * @var string The mysql user name
     */
    protected $_username;

    /**
     * @readwrite
     * @var string The mysql password
     */
    protected $_password;

    /**
     * @readwrite
     * @var string The mysql schema name
     */
    protected $_schema;

    /**
     * @readwrite
     * @var string The mysql port
     */
    protected $_port = '3306';

    /**
     * @readwrite
     * @var string The mysql charset to use
     */
    protected $_charset = 'utf8';

    /**
     * @readwrite
     * @var string  The mysql engine to use.
     */
    protected $_engine = 'InnoDB';

    /**
     * @readwrite
     * @var boolean The mysql connection state.
     */
    protected $_connected = false;
    
    /**
     * Connects to MySQL service.
     *
     * @return \Slick\Database\Connector\Mysql A self instance for chain
     *   method calls.
     */
    public function connect()
    {
        if (!$this->_isValidService()) {

            $this->_service =@ new \MySQLi(
                $this->_host,
                $this->_username,
                $this->_password,
                $this->_schema,
                $this->_port
            );

            if ($this->_service->connect_errno) {
                throw new Exception\ServiceException(
                    "Unable to connect to database service. ".
                    $this->_service->connect_error
                );
            }

            $this->_connected = true;
        }
        return $this;
    }

    /**
     * Disconnects from MySQL service
     * 
     * @return \Slick\Database\Connector\Mysql A self instance for chain
     *   method calls.
     */
    public function disconnect()
    {
        if ($this->_isValidService()) {
            $this->_service->close();
        }
        $this->_connected = false;
        return $this;
    }

    /**
     * Escapes the provided value to make it safe for queries.
     *
     * @param string $value The value to escape.
     * 
     * @return string A safe string for queries.
     */
    public function escape($value)
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid service."
            );
        }
        return $this->_service->real_escape_string($value);
    }

    /**
     * Executes the provided SQL statement.
     *
     * @param string $sql The SQL statment to execute.
     * 
     * @return mixed The \MySQLi::query() result.
     * @see \MySQLi::query()
     */
    public function execute($sql)
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid service."
            );
        }
        return $this->_service->query($sql);
    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid service."
            );
        }
        return $this->_service->affected_rows;
    }

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     */
    public function getLastError()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid service."
            );
        }

        return $this->_service->error;
    }

    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last insertd ID value.
     */
    public function getLastInsertId()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid service."
            );
        }
        return $this->_service->insert_id;
    }

    public function query()
    {
        
    }
    
    /**
     * Checks if connected to a database server.
     *
     * @return boolean The connection state. True if connected, false otherwise.
     */
    protected function _isValidService()
    {
        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof \MySQLi;

        if ($this->isConnected() && $isInstance && !$isEmpty) {
            return true;
        }

        return false;
    }
}
