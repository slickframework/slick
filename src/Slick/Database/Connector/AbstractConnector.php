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

use Slick\Common\BaseSingleton;

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
     * @read
     * @var boolean A flag that indicates the connection state
     */
    protected $_connected = false;


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
    public function query()
    {

    }
    
    /**
     * Executes the provided SQL statement.
     *
     * @param string $sql The SQL statment to execute.
     * 
     * @return \PDOStatement The connector response from server.
     */
    public function execute($sql)
    {

    }
    
    /**
     * Returns the ID of the last row to be inserted.
     *
     * @return integer The last insertd ID value.
     */
    public function getLastInsertId()
    {

    }

    /**
     * Returns the number of rows affected by the last SQL query executed.
     *
     * @return integer The number of rows affected by last query.
     */
    public function getAffectedRows()
    {

    }

    /**
     * Returns the last error of occur.
     *
     * @return string The last error of occur.
     */
    public function getLastError()
    {
        
    }

    /**
     * Sets the dsn to use with PDO initializarion
     * 
     * @return string The DSN string to initilize the PDO class.
     */
    abstract public function getDsn();
}