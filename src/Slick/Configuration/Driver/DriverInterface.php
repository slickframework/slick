<?php

/**
 * DriverInterface
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration\Driver;

/**
 * DriverInterface, defines a configuration driver
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DriverInterface
{
    
    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key     The key used to store the value in configuration
     * @param mixed  $default The default value if no value was stored.
     * 
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null);

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     * 
     * @return \Slick\Configuration\DriverInterface Self instance for
     *   method call chains.
     */
    public function set($key, $value);

}