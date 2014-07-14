<?php

/**
 * Database adapter
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database;

use Slick\Common\Base,
    Slick\Database\Adapter\AdapterInterface,
    Slick\Database\Exception\InvalidArgumentException;

use ReflectionClass;

/**
 * Database adapter factory class
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
final class Adapter extends Base
{
    /**
     * @readwrite
     * @var string Driver name or adapter class name
     */
    protected $_driver = 'Mysql';

    /**
     * @readwrite
     * @var array An associative array with options for adapter initialization
     */
    protected $_options = [];

    /**
     * @var array List of known adapters
     */
    private $_knownAdapters = [
        'mysql' => 'Slick\Database\Adapter\MysqlAdapter',
        'sqlite' => 'Slick\Database\Adapter\SqliteAdapter'
    ];

    /**
     * Initializes an adapter with provided options
     *
     * @throws Exception\InvalidArgumentException If the driver is null,
     *  unknown or if the adapter class name provided as driver does not
     * implements the Slick\Database\Adapter\AdapterInterface interface.
     *
     * @return AdapterInterface
     */
    public function initialize()
    {
        $adapter = null;
        if (is_null($this->_driver)) {
            // No name given for driver initialization
            throw new InvalidArgumentException(
                "Trying to initialize an invalid database adapter."
            );
        }

        if (class_exists($this->_driver)) {
            // Class name given, check if it is an adapter
            $reflection = new ReflectionClass($this->_driver);
            if (
                !$reflection->implementsInterface(
                    'Slick\Database\Adapter\AdapterInterface'
                )
            ) {
                throw new InvalidArgumentException(
                    "The adapter class name {$this->_driver} does not " .
                    "implement Slick\Database\Adapter\AdapterInterface " .
                    "interface."
                );
            }

            return $reflection->newInstanceArgs([$this->_options]);
        }

        switch (strtolower($this->_driver)) {
            case 'mysql':
                $adapter = $this->_createAdapter('mysql');
                break;

            case 'sqlite':
                $adapter = $this->_createAdapter('sqlite');
                break;

            default:
                throw new InvalidArgumentException(
                    "Trying to initialize an unknown database adapter."
                );
        }

        return $adapter;
    }

    /**
     * @param string $driver
     * @return AdapterInterface
     */
    private function _createAdapter($driver)
    {
        $reflection = new ReflectionClass($this->_knownAdapters[$driver]);
        return $reflection->newInstanceArgs([$this->_options]);
    }
} 