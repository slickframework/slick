<?php

/**
 * Adapter factory test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database;

use Slick\Database\Adapter;
use Slick\Database\Exception as DatabaseException;

/**
 * Adapter factory test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AdapterFactoryTest extends \Codeception\TestCase\Test
{
    /**
     * Create and initialize a Mysql adapter
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function initializeMysqlAdapter()
    {
        $adapter = new Adapter(['driver' => 'Mysql', 'options' => ['autoConnect' => false]]);
        $this->assertInstanceOf('Slick\Database\Adapter', $adapter);
        $adapter = $adapter->initialize();
        $this->assertInstanceOf('Slick\Database\Adapter\AdapterInterface', $adapter);

        $adapter = new Adapter(['driver' => '_unknown_']);
        try {
            $adapter->initialize();
            $this->fail("Initializing an unknown database driver should raise an exception");
        } catch (DatabaseException $exp) {
            $this->assertInstanceOf('Slick\Database\Exception\InvalidArgumentException', $exp);
        }

        $adapter = new Adapter(['driver' => null]);
        $adapter->initialize();
    }

    /**
     * Initialize a custom adapter class
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function initializeACustomAdapter()
    {
        $adapter = new Adapter(
            [
                'driver' => 'Database\CustomAdapter',
                'options' => ['autoConnect' => false]
            ]
        );
        $adapter = $adapter->initialize();
        $this->assertInstanceOf('Database\CustomAdapter', $adapter);

        $adapter = new Adapter(
            [
                'driver' => 'Database\CustomClass'
            ]
        );
        $adapter->initialize();
    }
}

/**
 * Custom Adapter
 * @package Database
 */
class CustomAdapter extends Adapter\MysqlAdapter
{

}

/**
 * Custom Class (not an adapter)
 * @package Database
 */
class CustomClass
{

}