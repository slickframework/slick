<?php

/**
 * Memcached
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Driver;

use Memcache;
use Slick\Cache\DriverInterface;
use Slick\Cache\Exception\ServiceException;

/**
 * Use memcached daemon to store cache data
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Memcached extends AbstractDriver
{
    /**
     * @read
     * @var Memcache A Memcache instance.
     */
    protected $_service;

    /**
     * @readwrite
     * @var string Memcached host
     */
    protected $_host = '127.0.0.1';

    /**
     * @readwrite
     * @var string Memcached port
     */
    protected $_port = '11211';

    /**
     * @readwrite
     * @var string Prefix for keys
     */
    protected $_prefix = 'slick_';

    /**
     * @readwrite
     * @var boolean Service connection state
     */
    protected $_connected = false;

    /**
     * Overrides the default constructor to connect to the memcached service
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->connect();
    }

    /**
     * Connects to Memcached service.
     *
     * @throws ServiceException If any error occurs during
     *  Memcache initialization
     *
     * @return Memcached A self instance for chaining method calls.
     *
     */
    public function connect()
    {
        try {
            $this->_service = new Memcache();
            $this->_service->connect($this->_host, $this->_port);
            $this->_connected = true;
        } catch (\Exception $e) {
            throw new ServiceException(
                "Unable to connect to Memcached service"
            );
        }

        return $this;
    }

    /**
     * Lazy loading of Memcached service.
     *
     * @return Memcache
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     * Disconnects the Memcached service.
     *
     * @return Memcache A self instance for chaining
     *   method calls.
     */
    public function disconnect()
    {
        if ($this->_isValidService()) {
            $this->getService()->close();
            $this->_connected = false;
        }

        return $this;
    }

    /**
     * Retrieves a previously stored value.
     *
     * @param String $key The key under witch value was stored.
     * @param mixed $default The default value, if no value was stored before.
     *
     * @throws ServiceException If you are trying to set a cache
     *   value without connecting to memcached service first.
     *
     * @return mixed The stored value or the default value if it was
     *  not found on service cache.
     *
     */
    public function get($key, $default = null)
    {
        if (!$this->_isValidService()) {
            throw new ServiceException(
                "Not connected to a valid memcached service."
            );
        }

        $value = $this->getService()
            ->get($this->_prefix.$key);

        if ($value) {
            return $value;
        }

        return $default;
    }

    /**
     * Set/stores a value with a given key.
     *
     * @param String  $key      The key where value will be stored.
     * @param mixed   $value    The value to store.
     * @param integer $duration The live time of cache in seconds.
     * 
     * @return Memcached A self instance for chaining method calls.
     *
     * @throws ServiceException If you are trying to set a cache
     *   value without connecting to memcached service first.
     */
    public function set($key, $value, $duration = -999)
    {
        if (!$this->_isValidService()) {
            throw new ServiceException(
                "Not connected to a valid memcached service."
            );
        }

        $duration = ($duration < 0) ? $this->_duration : $duration;
        $this->getService()->set(
            $this->_prefix.$key,
            $value,
            MEMCACHE_COMPRESSED, 
            $duration
        );

        $this->_addKey($key);
        return $this;
    }

    /**
     * Erase the value stored wit a given key.
     *
     * Erase the value stored with a given key.
     *
     * You can use the "?" and "*" wildcards to delete all matching keys.
     * The "?" means a place holders for one unknown character, the "*" is
     * a place holder for various characters.
     *
     * @param string $pattern The key under witch value was stored.
     * 
     * @return Memcached A self instance for chaining method calls.
     *
     * @throws ServiceException If you are trying to set a cache
     *   value without connecting to memcached service first.
     */
    public function erase($pattern)
    {
        if (!$this->_isValidService()) {
            throw new ServiceException(
                "Not connected to a valid memcached service."
            );
        }

        $keys = $this->getKeys($pattern);

        foreach ($keys as $key) {
            $this->getService()->delete($this->_prefix.$key);
            $this->_removeKey($key);
        }


        return $this;
    }

    /**
     * Flushes all values controlled by this cache driver
     *
     * @return DriverInterface A self instance for chaining method calls.
     *
     * @throws ServiceException If you are trying to set a cache
     *   value without connecting to memcached service first.
     */
    public function flush()
    {
        if (!$this->_isValidService()) {
            throw new ServiceException(
                "Not connected to a valid memcached service."
            );
        }

        $this->getService()->flush();
        return $this;
    }

    /**
     * Checks if service is a valid instance and its connected.
     *
     * @return boolean True if service is connected and valid, false otherwise.
     */
    protected function _isValidService()
    {
        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof Memcache;

        if ($this->_connected && $isInstance && !$isEmpty) {
            return true;
        }

        return false;
    }

    /**
     * Disconnects from service when done
     *
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}