<?php

/**
 * DriverInterface
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache;

/**
 * Interface for a cache driver.
 *
 * @package   Slick\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DriverInterface
{
    
    /**
     * Retrieves a previously stored value.
     *
     * @param String $key     The key under witch value was stored.
     * @param mixed  $default The default value, if no value was stored before.
     * 
     * @return mixed The stored value or the default value if it was
     *  not found on service cache.
     */
    public function get($key, $default = false);

    /**
     * Set/stores a value with a given key.
     *
     * @param String  $key      The key where value will be stored.
     * @param mixed   $value    The value to store.
     * @param integer $duration The live time of cache in seconds.
     * 
     * @return self A self instance for chaining method calls.
     */
    public function set($key, $value, $duration = -1);

    /**
     * Erase the value stored with a given key.
     *
     * You can use the "?" and "*" wildcards to delete all matching keys.
     * The "?" means a place holders for one unknown character, the "*" is
     * a place holder for various characters.
     *
     * @param String $key The key under witch value was stored.
     * 
     * @return self A self instance for chaining method calls.
     */
    public function erase($key);

    /**
     * Flushes all values controlled by this cache driver
     *
     * @return self A self instance for chaining method calls.
     */
    public function flush();

}