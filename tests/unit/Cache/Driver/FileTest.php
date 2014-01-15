<?php

/**
 * File cahe driver test case
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
 * File cahe driver test case
 * 
 * @package    Test\Cache\Driver
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class FileTest extends \Codeception\TestCase\Test
{

    /**
     * Cratea a file driver
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

}