<?php

/**
 * Database
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database;

use Slick\Common\Base,
    Slick\Database\Exception;
use Slick\Database\Connector\ConnectorInterface;

/**
 * Database is a factory for a database connector object.
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Database extends Base
{
    /**
     * @readwrite
     * @var string Contains the name of the connector to use
     */
    protected $_type = 'mysql';

    /**
     * @readwrite
     * @var array A list of connector options
     */
    protected $_options = null;

    /**
     * @read
     * @var array A list of supported types
     */
    protected $_supportedTypes = array('mysql', 'sqlite');

    /**
     * Initializes an database connector.
     *
     * @throws Exception\InvalidArgumentException
     * @return ConnectorInterface The database connector
     *  instance.
     */
    public function initialize()
    {
        $connector = null;
        if (!$this->_type) {
            throw new Exception\InvalidArgumentException(
                "Trying to initialize a database connector with an undefined ".
                "connector type."
            );
        }



        if (
            !in_array(strtolower($this->_type), $this->_supportedTypes) &&
            class_exists($this->_type)
        ) {
            $class = $this->_type;
            $connector = call_user_func_array(
                [$class, 'getInstance'],
                [$this->_options]
            );
            if (
                !is_a(
                    $connector,
                    'Slick\Database\Connector\ConnectorInterface'
                )
            ) {
                throw new Exception\InvalidArgumentException(
                    "Class {$class} doesn't implement " .
                    "Slick\Database\Connector\ConnectorInterface interface."
                );
            }
            return $connector;
        }

        switch (strtolower($this->_type)) {
            case 'mysql':
                $connector = Connector\Mysql::getInstance($this->_options);
                break;

            case 'sqlite':
                $connector = Connector\SQLite::getInstance($this->_options);
                break;
            
            default:
                throw new Exception\InvalidArgumentException(
                    "Trying to initialize a database connector with an ".
                    "unknown connector type."
                );
        }

        return $connector;
    }
}