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

/**
 * Memcached
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
    protected $_isConnected = false;

    /**
     * Connects to Memcached service.
     *
     * @return Memcached A sefl instance for chaining method calls.
     *
     * @throws Exception\Service If an error occours when trying
     *   to connect to memcached service.
     */
    public function connect()
    {
        try {
            $this->_service = new Memcache();
            $this->_service->connect($this->host, $this->port);
            $this->isConnected = true;
        } catch (\Exception $e) {
            throw new Exception\ServiceException(
                "Unable to connecto to Memcached service"
            );
        }

        return $this;
    }

    /**
     * Lazy loading of Memcached service. 
     * @return memcached
     */
    public function getService()
    {
        if (empty($this->_service)) {
            $this->connect();
        }
        return $this->_service;
    }

    /**
     * Disconnects the Memcached service.
     *
     * @return Slick\Cache\Driver\Memcached A sefl instance for chaining
     *   method calls.
     */
    public function disconnect()
    {
        if ($this->_isValidService()) {
            $this->service->close();
            $this->isConnected = false;
        }

        return $this;
    }

    /**
     * Retrives a previously stored value.
     *
     * @param String $key     The key under witch value was stored.
     * @param mixed  $default The default value, if no value was stored before.
     * 
     * @return mixed The stored value or the default value if it was
     *  not found on service cache.
     *
     * @throws Exception\Service If you are trying to set a cache
     *   values without connecting to memcached service first.
     */
    public function get($key, $default = null)
    {
        if (!$this->_isValidService()) {
            throw new Exception\Service(
                "Not connected to a valid Memcached service"
            );
        }

        $value = $this->service->get($this->prefix.$key, MEMCACHE_COMPRESSED);

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
     * @param integer $duration The live time of cache in secondes.
     * 
     * @return Memcached A sefl instance for chaining method calls.
     *
     * @throws Exception\Service If you are trying to set a cache
     *   values without connecting to memcached service first.
     */
    public function set($key, $value, $duration = 120)
    {
        if (!$this->_isValidService()) {
            throw new Exception\Service(
                "Not connected to a valid Memcached service"
            );
        }
        $this->service->set(
            $this->prefix.$key,
            $value,
            MEMCACHE_COMPRESSED, 
            $duration
        );
        return $this;
    }

    /**
     * Erase the value stored wit a given key.
     *
     * @param String $key The key under witch value was stored.
     * 
     * @return Slick\Cache\Driver\Memcached A sefl instance for chaining
     *   method calls.
     *
     * @throws Slick\Cache\Exception\Service If you are trying to erase a cache
     *   values without connecting to memcached service first.
     */
    public function erase($key)
    {
        if (!$this->_isValidService()) {
            throw new Exception\Service(
                "Not connected to a valid Memcached service"
            );
        }
        $this->service->delete($this->prefix.$key);
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

        if ($this->isConnected && $isInstance && !$isEmpty) {
            return true;
        }

        return false;
    }

    /**
     * Disconnects from service when done
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}