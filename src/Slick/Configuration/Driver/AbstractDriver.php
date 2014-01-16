<?php

/**
 * AbstractDriver
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration\Driver;

use Slick\Common\Base;

/**
 * AbstractDriver
 *
 * @package   Slick\Configuration\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDriver extends Base implements DriverInterface
{

    /**
     * @readwrite
     * @var array Loaded data
     */
    protected $_data = array();

    /**
     * @readwrite
     * @var string The full path and name for configuration file.
     */
    protected $_file = null;
    
    /**
     * Overrides base contructor to call load method
     * 
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_load();
    }

    /**
     * Loads the data into this configuration driver
     */
    abstract protected function _load();

    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key     The key used to store the value in configuration
     * @param mixed  $default The default value if no value was stored.
     * 
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null)
    {
        return $this->_get($key, $default, $this->_data);
    }

    /**
     * Recursive method to parse dot notation keys and retrive the value
     * 
     * @param string $key     The key to search
     * @param mixed  $default The value if key doesn't exists
     * @param array  $data    The data to search
     * 
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    protected function _get($key, $default, $data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);

        if (isset($data[$first])) {
            if (count($parts) > 0) {
                $newKey = implode('.', $parts);
                return $this->_get($newKey, $default, $data[$first]);
            } else {
                return $data[$first];
            }
        }

        return $default;
    }

    /**
     * Set/Store the provided value with a given key.
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     * 
     * @return \Slick\Configuration\AbstractDriver Self instance for
     *   method call chains.
     */
    public function set($key, $value)
    {
        return $this->_set($key, $value, $this->_data);
    }

    /**
     * Recursive method to parse dot notation keys and set the value
     * 
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     * @param array  $data  The data to search
     *
     * @return \Slick\Configuration\AbstractDriver Self instance for
     *   method call chains.
     */
    protected function _set($key, $value, &$data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);

        if (count($parts) > 0) {
            $newKey = implode('.', $parts);
            $data[$first] = array();
            return $this->_set($newKey, $value, $data[$first]);
        }

        $data[$first] = $value;
        return $this;
    }
}