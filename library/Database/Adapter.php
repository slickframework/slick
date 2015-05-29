<?php

/**
 * Adapter
 *
 * @package Slick\Database
 * @author    Filipe Silva <filipe.silva@sata.pt>
 * @copyright 2014-2015 Grupo SATA
 * @since     v0.0.0
 */

namespace Slick\Database;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Adapter\MysqlAdapter;
use Slick\Database\Adapter\SqliteAdapter;
use Slick\Database\Exception\InvalidArgumentException;

/**
 * Adapter factory class
 *
 * @package Slick\Database
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $driver
 * @property array $options
 *
 * @property-read string $driverClass
 */
final class Adapter extends Base
{

    /**#@+
     * Known driver alias
     */
    const DRIVER_MYSQL  = 'mysql';
    const DRIVER_SQLITE = 'sqlite';
    /**#@- */

    /**
     * @readwrite
     * @var string Adapter driver
     */
    protected $driver;

    /**
     * @readwrite
     * @var array A associative array of options for adapter initialization
     */
    protected $options;

    /**
     * @read
     * @var string
     */
    protected $driverClass;

    /**
     * @var array Known classes alias map
     */
    private $alias = [
        self::DRIVER_MYSQL  => "Slick\\Database\\Adapter\\MysqlAdapter",
        self::DRIVER_SQLITE => "Slick\\Database\\Adapter\\SqliteAdapter",
    ];

    /**
     * @var string The interface class to check
     */
    private $interface = "Slick\\Database\\Adapter\\AdapterInterface";

    /**
     * Creates a new adapter based on provided options.
     *
     * Options should contain the following structure:
     *
     * 'driver' => The adapter driver name. You can use the constants for the
     *             known alias names like DRIVER_MYSQL or DRIVER_SQLITE.
     *             You can also create you adapterInterface implementation. In
     *             this case you should enter the class FQN.
     * 'options => The driver options. This should be an associative array
     *             containing the properties for the driver you are creating.
     *             This are normally the connection settings.
     *
     * @see: MysqlAdapter
     * @see: SqliteAdapter
     *
     * @param array $options
     * @return AdapterInterface|MysqlAdapter|SqliteAdapter
     */
    public static function create(array $options)
    {
        /** @var Adapter $factory */
        $factory = new static($options);
        return $factory->initialize();
    }

    /**
     * Initializes a database adapter with provided driver and options
     *
     * @throws InvalidArgumentException If the provided class name does
     *  not implements the adapter interface or it does not exists.
     *
     * @return MysqlAdapter|SqliteAdapter|AdapterInterface
     */
    public function initialize()
    {
        $class = $this->getDriverClass();
        $this->checkDriverClass();

        return new $class($this->options);
    }

    /**
     * Returns the correct adapter class for provided driver name
     *
     * @return string
     */
    public function getDriverClass()
    {
        if (is_null($this->driverClass)) {
            $this->driverClass = $this->driver;
            if (array_key_exists($this->driver, $this->alias)) {
                $this->driverClass = $this->alias[$this->driver];
            }
        }
        return $this->driverClass;
    }

    /**
     * Checks if the provided class implements the adapter interface
     *
     * @throws InvalidArgumentException If the provided class name does
     *  not implements the adapter interface or it does not exists.
     *
     * @see Adapter::$interface
     */
    private function checkDriverClass()
    {
        if (!class_exists($this->driverClass)) {
            throw new InvalidArgumentException(
                "The class '{$this->driverClass}' does not ".
                "exists."
            );
        }

        $inspector = Inspector::forClass($this->driverClass);

        if (
            !$inspector->getReflection()
                ->implementsInterface($this->interface)
        ) {
            throw new InvalidArgumentException(
                "The class '{$this->driverClass}' does not ".
                "implements '{$this->interface}'."
            );
        }
    }
}