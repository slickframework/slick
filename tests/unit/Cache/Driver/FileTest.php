<?php

/**
 * File cache driver test case
 * 
 * @package    Test\Cache\Driver
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Cache\Driver;

use Codeception\Util\Stub,
    Slick\Cache\Driver\File as Driver;

/**
 * File cache driver test case
 * 
 * @package    Test\Cache\Driver
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class FileTest extends \Codeception\TestCase\Test
{

    /**
     * Create a file driver
     * @test
     */
    public function createFileDriver()
    {
        $cache = new Driver();
        $this->assertSame($cache, $cache->initialize());
        $this->assertEquals(120, $cache->getDuration());
        $this->assertEquals('', $cache->prefix);
    }

    /**
     * Write some values in the cache
     * @test
     */
    public function settingValues()
    {
        $cache = new Driver();
        $path = dirname(dirname(dirname(__DIR__))) . '/app/Temp';
        $cache->path = $path;
        $cache->set('test', array(1, 2, 3));
        $file = $path . "/cache/test.tmp";
        $this->assertTrue(file_exists($file));
        if (file_exists($file))
            unlink($file);
    }

    /**
     * Read values from cache
     * @test
     */
    public function getValues()
    {
        $cache = new Driver();
        $path = dirname(dirname(dirname(__DIR__))) . '/app/Temp';
        $cache->path = $path;
        $data = array('foo', 'bar');
        $cache->set('foo', $data);
        $this->assertEquals($data, $cache->get('foo', false));
        $cache->set('foo', $data, 0);
        $this->assertFalse($cache->get('foo', false));
        $this->assertTrue($cache->get('bar', true));
        $file = $path . "/cache/foo.tmp";
        if (file_exists($file))
            unlink($file);
    }

    /**
     * Erase a cache value.
     * @test
     */
    public function eraseValues()
    {
        $cache = new Driver();
        $path = dirname(dirname(dirname(__DIR__))) . '/app/Temp';
        $cache->path = $path;

        $data = array('foo', 'bar');
        $cache->set('foo', $data);
        $this->assertEquals($data, $cache->get('foo', false));

        $result = $cache->erase('foo');
        $this->assertSame($cache, $result);
        $this->assertFalse($cache->get('foo', false));
        $file = $path . "/cache/foo.tmp";
        $this->assertFalse(file_exists($file));

    }

    /**
     * Flush a cache value.
     * @test
     */
    public function flushValues()
    {
        $cache = new Driver();
        $path = dirname(dirname(dirname(__DIR__))) . '/app/Temp';
        $cache->path = $path;

        $data = array('foo', 'bar');
        $cache->set('foo', $data);
        $this->assertEquals($data, $cache->get('foo', false));

        $result = $cache->flush();
        $this->assertSame($cache, $result);
        $this->assertFalse($cache->get('foo', false));
        $file = $path . "/cache/foo.tmp";
        $this->assertFalse(file_exists($file));
    }

}