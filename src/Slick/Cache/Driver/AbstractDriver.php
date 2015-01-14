<?php

/**
 * AbstractDriver
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Driver;

use Slick\Common\Base,
    Slick\Cache\DriverInterface;

/**
 * Wrapper for common properties and methods among cache drivers
 *
 * @package   Slick\Cache\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string   $prefix   Prefix for cache key
 * @property integer  $duration Number of seconds for cache expiry
 *
 * @property-read string[] $keys
 */
abstract class AbstractDriver extends Base implements DriverInterface
{

    /**
     * @readwrite
     * @var string The prefix for cache key
     */
    protected $_prefix = '';

    /**
     * @readwrite
     * @var integer The number of seconds for cache expiry
     */
    protected $_duration = 120;


    /**
     * Handle the initialization of an already initialized driver
     * 
     * @return AbstractDriver
     */
    public function initialize()
    {
        return $this;
    }

    /**
     * Return current keys in use
     *
     * @param string|null $pattern
     * @return string[]
     */
    public function getKeys($pattern = null)
    {
        $keys = $this->get('__stored_keys__', []);
        if (is_null($pattern)) {
            return $keys;
        }

        $matches = [];
        $pattern = $this->_normalizePattern($pattern);
        foreach ($keys as $key) {
            if (preg_match($pattern, $key)) {
                $matches[] = $key;
            }
        }

        return $matches;
    }

    /**
     * Adds a key to the list of keys used
     *
     * @param string $key
     *
     * @return AbstractDriver A self instance for chain calls
     */
    protected function _addKey($key)
    {
        if ($key == '__stored_keys__') {
            return $this;
        }

        $keys = $this->get('__stored_keys__', []);
        if (!in_array($key, $keys)) {
            array_push($keys, $key);
        }
        $this->set('__stored_keys__', $keys, 24*60*60);
        return $this;
    }

    /**
     * Removes a key to the list of keys used
     * @param string $key
     *
     * @return AbstractDriver
     */
    protected function _removeKey($key)
    {
        $keys = $this->get('__stored_keys__', []);
        if (in_array($key, $keys)) {
            $reverse = array_flip($keys);
            unset($reverse[$key]);
            $this->set('__stored_keys__', array_keys($reverse));
        }
        return $this;
    }

    /**
     * Creates a regular expression from given pattern
     *
     * @param string $pattern
     * @return string
     */
    protected function _normalizePattern($pattern)
    {
        $normalized = str_replace(['?', '*'], ['.', '.*'], $pattern);
        return "/{$normalized}/i";
    }


}