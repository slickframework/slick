<?php

/**
 * Schema loader factory
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema;

use ReflectionClass;
use Slick\Common\BaseMethods;
use Slick\Database\Sql\Dialect;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Exception\InvalidArgumentException;

/**
 * Schema loader factory
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property AdapterInterface $adapter
 * @property string           $class
 *
 * @method AdapterInterface getAdapter() Retrieves the database adapter
 * @method Loader setAdapter(AdapterInterface $adapter) Set the adapter
 * @method Loader setClass($className) Sets loader class name
 */
class Loader
{

    /**
     * Factory behavior methods from Slick\Common\Base class
     */
    use BaseMethods;

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * @readwrite
     * @var string
     */
    protected $_class;

    /**
     * Easy construction with base methods
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }

    /**
     * A loaders class map
     * @var array
     */
    protected static $_classMap = [
        Dialect::MYSQL => 'Slick\Database\Schema\Loader\Mysql',
        Dialect::SQLITE => 'Slick\Database\Schema\Loader\Sqlite',
        Dialect::STANDARD => 'Slick\Database\Schema\Loader\Standard',
    ];

    /**
     * Returns current class name
     */
    public function getClass()
    {
        if (is_null($this->_class)) {
            $this->_class = $this->_getClassName();
        }
        return $this->_class;
    }

    /**
     * Queries the database and retrieves the schema object that maps it
     *
     * @return SchemaInterface
     *
     * @throws InvalidArgumentException If the adapter is not defined
     * @throws InvalidArgumentException If the loader class is not defined
     */
    public function load()
    {
        $this->_checkAdapter();

        if (!class_exists($this->getClass())) {
            throw new InvalidArgumentException(
                "The loader class '{$this->getClass()}' is not defined."
            );
        }

        $reflexion = new ReflectionClass($this->getClass());

        /** @var LoaderInterface $loader */
        $args = [['adapter' => $this->_adapter]];
        $loader = $reflexion->newInstanceArgs($args);

        $className = '\Slick\Database\Schema\LoaderInterface';

        if (!($loader instanceof LoaderInterface)) {
            throw new InvalidArgumentException(
                "The loader class '{$this->getClass()}' does not " .
                "implement {$className}."
            );
        }
        return $loader->getSchema();
    }

    /**
     * Retrieves the class name for given adapter
     *
     * @return string
     *
     * @throws InvalidArgumentException If the adapter is not defined
     */
    protected function _getClassName()
    {
        $this->_checkAdapter();
        return static::$_classMap[$this->_adapter->getDialect()];
    }

    /**
     * Checks if the adapter is defined
     *
     * @throws InvalidArgumentException If the adapter is not defined
     */
    protected function _checkAdapter()
    {
        $notDefined = !is_object($this->_adapter);
        $isAdapter = $this->_adapter instanceof AdapterInterface;

        if ($notDefined || !$isAdapter) {
            throw new InvalidArgumentException(
                "You need to set the database adapter for this schema loader."
            );
        }
    }
}
