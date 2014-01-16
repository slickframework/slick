<?php

/**
 * Cache factory test case
 * 
 * @package    Test\Cache
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Cache;

use Codeception\Util\Stub,
    Slick\Cache\Cache,
    Slick\Cache\DriverInterface;

/**
 * Cache factory test case
 * 
 * @package    Test\Cache
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class CacheTest extends \Codeception\TestCase\Test
{

    /**
     * Initialize a cache driver
     * @test
     * @expectedException Slick\Cache\Exception\InvalidDriverException
     */
    public function initializeDriver()
    {
        $cache = new Cache();
        $driver = $cache->initialize();
        $this->assertInstanceOf("Slick\Cache\DriverInterface", $driver);
        $this->assertInstanceOf("Slick\Cache\Driver\File", $driver);
        $cache->setClass('Other')->initialize();
    }

    /**
     * Initialize a custom driver
     * @test
     * @expectedException Slick\Cache\Exception\InvalidDriverException
     */
    public function initializeCustomClass()
    {
        $cache = new Cache(array('class' => '\Cache\CustomDriver'));
        $driver = $cache->initialize();
        $this->assertInstanceOf("\Cache\CustomDriver", $driver);

        $cache->setClass("\StdClass");
        $driver = $cache->initialize();
    }

}

class CustomDriver implements DriverInterface
{

    /**
     * Retrives a previously stored value.
     *
     * @param String $key     The key under witch value was stored.
     * @param mixed  $default The default value, if no value was stored before.
     * 
     * @return mixed The stored value or the default value if it was
     *  not found on service cache.
     */
    public function get($key, $default = null)
    {

    }

    /**
     * Set/stores a value with a given key.
     *
     * @param String  $key      The key where value will be stored.
     * @param mixed   $value    The value to store.
     * @param integer $duration The live time of cache in seconds.
     * 
     * @return DriverInterface A sefl instance for chaining method calls.
     */
    public function set($key, $value, $duration = 120)
    {

    }

    /**
     * Erase the value stored wit a given key.
     *
     * @param String $key The key under witch value was stored.
     * 
     * @return DriverInterface A sefl instance for chaining method calls.
     */
    public function erase($key)
    {

    }
}