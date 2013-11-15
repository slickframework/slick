<?php

/**
 * AbstractConnector
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

use Slick\Common\BaseSingleton,
    Slick\Database\Exception;

/**
 * Abstract Connector is a basic implementation of Connector interface
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractConnector extends BaseSingleton implements ConnectorInterface
{

    /**
     * @readwrite
     * @var \Slick\Utility\PropertyList A list of connector options
     */
    protected $_options = null;

    /**
     * @readwrite
     * @var \PDO The PHP Data Object (PDO) object
     */
    protected $_dataObject = null;

    /**
     * @readwrite
     * @var boolean A flag that indicates the connection state
     */
    protected $_connected = false;

    /**
     * @write
     * @var string The class name of Data object
     */
    protected $_dboClass = '\PDO';

    /**
     * @readwrite
     * @var \PDOStatement Last statement used
     */
    protected $_lastStatement = null;


    /**
     * Connects to database service.
     *
     * @return \Slick\Database\Connector\ConnectorInterface
     *   A self instance for chain method calls.
     */
    abstract public function connect();
    
    /**
     * Disconnects from database service.
     * 
     * @return \Slick\Database\Connector\ConnectorInterface
     *   A self instance for chain method calls.
     */
    public function disconnect()
    {
        $this->_dataObject = null;
        $this->_connected = false;
        return $this;
    }

    /**
     * Returns a corresponding query instance.
     * 
     * @return \Slick\Database\Query\QueryInterface
     */
    abstract public function query();
    
    /**
     * Executes the provided SQL statement.
     *
     * @param string $sql The SQL statment to execute.
     * 
     * @return \PDOStatement The connector response from server.
     */
    public function execute($query)
    {
        
    }
    
    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return string The last insertd ID value.
     *
     * @throws \Slick\Database\Exception\ServiceException If this connector
     *  hasn't a valid PDO object
     */
    public function getLastInsertId()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid database service."
            );
        }

        return $this->_dataObject->lastInsertId();
    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     *
     * @throws \Slick\Database\Exception\ServiceException If this connector
     *  hasn't a valid PDO object
     */
    public function getAffectedRows()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid database service."
            );
        }

        if (!is_null($this->_lastStatement))
            return $this->_lastStatement->rowCount();
        
        return 0;
    }

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     *
     * @throws \Slick\Database\Exception\ServiceException If this connector
     *  hasn't a valid PDO object
     */
    public function getLastError()
    {
        if (!$this->_isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid database service."
            );
        }
        $errorInfo = $this->_dataObject->errorInfo();
        return $errorInfo[2];
    }

    /**
     * Sets the dsn to use with PDO initializarion
     * 
     * @return string The DSN string to initilize the PDO class.
     */
    abstract public function getDsn();

    /**
     * Checks if this connector has a valid data access object.
     *
     * @return boolean The connection state. True if connected.
     */
    protected function _isValidService()
    {
        $isService = !is_null($this->_dataObject);

        if ($isService && $this->isConnected()) {
            return true;
        }

        return false;
    }
}