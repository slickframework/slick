<?php

/**
 * Mysql
 * 
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

use Slick\Database\Exception;

/**
 * Mysql database connector
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends AbstractConnector
{

    /**
     * @readwrite
     * @var string The Mysql Server user name
     */
    protected $_username;

    /**
     * @readwrite
     * @var string The Mysql Server user password
     */
    protected $_password;

    /**
     * @readwrite
     * @var string The Mysql Server host name
     */
    protected $_hostname;

    /**
     * @readwrite
     * @var string The Mysql Server database
     */
    protected $_database;

    /**
     * @readwrite
     * @var integer The Mysql Server port
     */
    protected $_port = 3306;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar SingletonInterface $instance The *Singleton* instances
     *  of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return \Slick\Database\Connector\Mysql The *Singleton* instance.
     */
    public static function getInstance($options = array())
    {
        static $instance;
        if (
            !is_a(
                $instance,
                'Slick\Database\Connector\ConnectorInterface'
            )
        ) {
            $instance = new Mysql($options);
        }
        return $instance;
    }

    /**
     * Sets the dsn to use with PDO initializarion
     * 
     * @return string The DSN string to initilize the PDO class.
     */
    public function getDsn()
    {
        $dsn = "mysql:host=%s;dbname=%s";
        return sprintf(
            $dsn,
            $this->hostname,
            $this->database
        );
    }

    /**
     * Connects to database service.
     *
     * @return \Slick\Database\Connector\Myswl
     *   A self instance for chain method calls.
     */
    public function connect()
    {
        $className = $this->_dboClass;
        try {
            $this->dataObject = new $className(
                $this->getDsn(),
                $this->_username,
                $this->_password
            );
            $this->_connected = true;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            throw new Exception\ServiceException(
                "Error connecting to database: {$msg}",
                1,
                $e
            );
        }

        return $this;
    }

    /**
     * Returns a corresponding query instance.
     * 
     * @return \Slick\Database\Query\Mysql
     */
    public function query()
    {

    }
}