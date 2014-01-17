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
    */
   public function initializeDriver()
   {
    $cache = new Cache(array('class' => 'Memcached'));
    $cache = $cache->initialize();
    $this->assertInstanceOf('Slick\Cache\Driver\Memcached', $cache);
   }
}