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
    Slick\Database\Exception,
    Slick\Common\Inspector;

/**
 * Abstract Connector is a basic implementation of Connector interface
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractConnector extends BaseSingleton
    implements ConnectorInterface
{

    /**
     * @readwrite
     * @var array A list of connector options
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
     * Prevents a second call to initialize
     *
     * @return $this
     */
    public function initialize()
    {
        return $this;
    }


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
     * Returns a corresponding DDL query instance.
     * 
     * @return \Slick\Database\Query\QueryInterface
     */
    abstract public function ddlQuery();
    
    /**
     * Executes an SQL statement in a single function call, returning the
     * number of rows affected by the statement
     *
     * @param string $sql The SQL statement to execute.
     * 
     * @return integer The number of rows that were modified or deleted by
     * the SQL statement you issued
     *
     * @throws \Slick\Database\Exception\ServiceException If this connector
     *  has not a valid PDO object
     */
    public function execute($sql)
    {
        if (!$this->isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid database service."
            );
        }

        return $this->_dataObject->exec($sql);
    }
    
    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return string The last inserted ID value.
     *
     * @throws \Slick\Database\Exception\ServiceException If this connector
     *  has not a valid PDO object
     */
    public function getLastInsertId()
    {
        if (!$this->isValidService()) {
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
     *  has not a valid PDO object
     */
    public function getAffectedRows()
    {
        if (!$this->isValidService()) {
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
     *  has not a valid PDO object
     */
    public function getLastError()
    {
        if (!$this->isValidService()) {
            throw new Exception\ServiceException(
                "Not connected to a valid database service."
            );
        }
        $errorInfo = $this->_dataObject->errorInfo();
        return $errorInfo[2];
    }

    /**
     * Sets the dsn to use with PDO initialization
     * 
     * @return string The DSN string to initialize the PDO class.
     */
    abstract public function getDsn();

    /**
     * Magic method to handle undefined methods call.
     *
     * This method gives this connect to the ability to behave as a proxy for
     * the Slick\Database\Connector\AbstractConnector::$_dataObject.
     *
     * It check is it has the calling method defined and returns its execution
     * If the method its not define the \Slick\Base::__call() will be returned
     * as it can be a property getter or setter call.
     *
     * @see  Slick\Database\Connector\AbstractConnector::$_dataObject
     * @see  Slick\Base::__call()
     *
     * @param string $method The method name
     * @param array $arguments The arguments set with the calling
     *
     * @throws \Slick\Database\Exception\ServiceException if not connected to
     * a valida database service.
     *
     * @return mixed  The return of the used method.
     */
    public function __call($method, $arguments = array())
    {
        $podInspector = new Inspector('\PDO');
        if ($podInspector->hasMethod($method)) {
            
            if (!$this->isValidService()) {
                throw new Exception\ServiceException(
                    "Not connected to a valid database service."
                );
            }

            return call_user_func_array(
                array($this->_dataObject, $method),
                $arguments
            );
        }

        return parent::__call($method, $arguments);
    }

    /**
     * Checks if this connector has a valid data access object.
     *
     * @return boolean The connection state. True if connected.
     */
    public function isValidService()
    {
        $isService = !is_null($this->_dataObject);

        if ($isService && $this->isConnected()) {
            return true;
        }

        return false;
    }
}