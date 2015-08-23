<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema;

use ReflectionClass;
use Slick\Common\Base;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Exception\InvalidSchemaLoaderClass;
use Slick\Database\Sql\Dialect;

/**
 * Schema loader factory
 *
 * @package Slick\Database\Schema
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property AdapterInterface $adapter   The base adapter for dialect detection
 * @property string           $className A LoaderInterface FQCN to initialize
 *
 * @method AdapterInterface getAdapter() Gets the adapter used in this factory
 */
final class Loader extends Base
{

    /**
     * Default loader class
     */
    const DEFAULT_CLASS = Dialect::STANDARD;

    /**
     * A loaders class map
     * @var array
     */
    protected static $classMap = [
        Dialect::MYSQL    => 'Slick\Database\Schema\Loader\Mysql',
        Dialect::SQLITE   => 'Slick\Database\Schema\Loader\Sqlite',
        Dialect::STANDARD => 'Slick\Database\Schema\Loader\Standard',
    ];

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @readwrite
     * @var string
     */
    protected $className;

    /**
     * Initialize loader class
     *
     * @return LoaderInterface
     */
    public function initialize()
    {
        $className = $this->getClassName();
        /** @var LoaderInterface $loader */
        $loader = new $className();
        if (!is_null($this->getAdapter())) {
            $loader->setAdapter($this->getAdapter());
        }
        return $loader;
    }

    /**
     * Gets custom FQCN class name
     *
     * @return string
     */
    public function getClassName()
    {
        if (is_null($this->className)) {
            $this->className = self::$classMap[self::DEFAULT_CLASS];
            $this->checkAdapter();
        }
        return $this->className;
    }

    /**
     * Sets the loader class name.
     *
     * @param string $className
     *
     * @return $this|self
     *
     * @throws InvalidSchemaLoaderClass if the provided class name is from a
     *                                  class that does not implement the
     *                                  Slick\Database\Schema\LoaderInterface
     */
    public function setClassName($className)
    {
        $class = new ReflectionClass($className);
        $interface = 'Slick\Database\Schema\LoaderInterface';
        if (!$class->implementsInterface($interface)) {
            throw new InvalidSchemaLoaderClass(
                "{$className} does not implement '{$interface}'"
            );
        }

        $this->className = $className;
        return $this;
    }

    /**
     * Check the adapter dialect to determine the loader class name
     */
    private function checkAdapter()
    {
        if (!is_null($this->adapter)) {
            $key = $this->adapter->getDialect();
            if (array_key_exists($key, self::$classMap))
            $this->className = self::$classMap[$key];
        }
    }
}
