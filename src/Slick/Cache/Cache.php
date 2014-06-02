<?php

/**
 * Cache
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache;

use Slick\Common\Base,
    Slick\Cache\Driver;

/**
 * Factory for cache driver creation
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $class   Driver name or class name.
 * @property array  $options A associative array with with driver properties
 * @property-read string[] $supportedTypes
 */
class Cache extends Base
{
    
    /**
     * @readwrite
     * @var string Cache driver or driver class name
     */
    protected $_class = 'file';

    /**
     * @readwrite
     * @var array Driver configuration options
     */
    protected $_options = array();

    /**
     * @read
     * @var array A list of supported types
     */
    protected $_supportedTypes = array('file', 'memcached');

    /**
     * Factory method to initialize a cache driver
     *
     * @param string $type
     * @param array  $options
     *
     * @return DriverInterface
     */
    public static function get($type = 'file', $options = [])
    {
        /** @var Cache $cache */
        $cache = new static(['class' => $type, 'options' => $options]);
        return $cache->initialize();
    }

    /**
     * Driver initialization
     * 
     * @return \Slick\Cache\DriverInterface A cache driver
     * 
     * @throws Exception\InvalidDriverException If the driver type or class 
     *  name isn't a valid cache driver.
     */
    public function initialize()
    {
        $driver = null;

        if (
            !in_array(strtolower($this->_class), $this->_supportedTypes) &&
            class_exists($this->_class)
        ) {
            $class = $this->_class;
            $driver = new $class($this->_options);
            if (!is_a($driver, 'Slick\Cache\DriverInterface')) {
                throw new Exception\InvalidDriverException(
                    "Class {$class} doesn't implement " .
                    "Slick\Cache\DriverInterface interface."
                );
            }
            return $driver;
        }

        switch (strtolower($this->_class)) {

            case 'file':            
                $driver = new Driver\File($this->_options);
                break;

            case 'memcached':            
                $driver = new Driver\Memcached($this->_options);
                break;

            default:
                throw new Exception\InvalidDriverException(
                    "Type {$this->_class} cache driver doesn't exists."
                );

        }
        return $driver;
    }
}