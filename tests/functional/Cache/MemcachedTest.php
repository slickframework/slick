<?php

/**
 * Memcached Functional test case
 *
 * @package   Test\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Cache;

use Codeception\Util\Stub;
use Slick\Cache\Cache;

/**
 * Memcached Functional test case
 *
 * @package   Test\Cache
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MemcachedTest extends \Codeception\TestCase\Test
{
   
    /**
     * Initialize driver
     * @test
     * @expectedException Slick\Cache\Exception\ServiceException
     */
    public function initializeDriver()
    {
        $cache = new Cache(array('class' => 'memcached'));
        $cache = $cache->initialize();
        $this->assertInstanceOf('Slick\Cache\Driver\Memcached', $cache);

        $falseCache = new Cache(
            array(
                'class' => 'Memcached',
                'options' => array(
                    'port' => 121212
                )
            )
        );
        $falseCache->initialize()->connect();
    }

    /**
     * Getting and setting cache values
     * @test
     */
    public function getAndSetValues()
    {
        $cache = new Cache(array('class' => 'memcached'));
        $cache = $cache->initialize();
        $expected = array('foo', 'bar');

        $result = $cache->set("foo", $expected);
        $this->assertSame($cache, $result);
        $this->assertEquals($expected, $cache->get('foo'));
        $this->assertFalse($cache->get('bar', false));
        $cache->set('zed', $expected, 1);
        sleep(2);
        $this->assertFalse($cache->get('zed', false));
        $cache->erase('foo');
    }

    /**
     * Erase cahche values
     * @test
     */
    public function eraseValues()
    {
        $cache = new Cache(array('class' => 'memcached'));
        $cache = $cache->initialize();
        $cache->set("foo", array('foo'));
        $this->assertSame($cache, $cache->erase('foo'));
        $this->assertFalse($cache->get('foo', false));
    }
}