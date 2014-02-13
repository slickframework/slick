<?php

/**
 * SQLite
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Connector;

use Slick\Database\Exception,
    Slick\Database\Query\Query,
    Slick\Database\Query\DDLQuery;

/**
 * SQLite Database connecto for SQLite database
 *
 * @package   Slick\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLite extends AbstractConnector implements ConnectorInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $_file = ':memory:';

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar SingletonInterface $instance The *Singleton* instances
     *  of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return \Slick\Database\Connector\SQLite The *Singleton* instance.
     */
    public static function getInstance($options = array())
    {
        static $instance;

        if (is_null($instance)) {
            $instance = array();
        }

        $key = md5(serialize($options));

        if (
            !isset($instance[$key]) ||
            !is_a(
                $instance[$key],
                'Slick\Database\Connector\ConnectorInterface'
            )
        ) {
            $instance[$key] = new SQLite($options);
        }
        return $instance[$key];
    }

    /**
     * Sets the dsn to use with PDO initializarion
     * 
     * @return string The DSN string to initilize the PDO class.
     */
    public function getDsn()
    {
        $dsn = "sqlite:%s";
        return sprintf(
            $dsn,
            $this->file
        );
    }

    /**
     * Connects to database service.
     *
     * @return \Slick\Database\Connector\SQLite
     *   A self instance for chain method calls.
     */
    public function connect()
    {
        $className = $this->_dboClass;
        try {
            $this->dataObject = new $className($this->getDsn());
            $this->dataObject->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
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
     * @param string $sql The sql string to perform
     * 
     * @return \Slick\Database\Query\Query
     */
    public function query($sql = null)
    {
        return new Query(
            array(
                'dialect' => 'SQLite',
                'connector' => $this,
                'sql' => $sql
            )
        );
    }

    /**
     * Returns a corresponding DDL query instance.
     * 
     * @return \Slick\Database\Query\DDLQuery
     */
    public function ddlQuery($sql = null)
    {
        return new DDLQuery(
            array(
                'dialect' => 'SQLite',
                'connector' => $this,
                'sql' => $sql
            )
        );
    }
}