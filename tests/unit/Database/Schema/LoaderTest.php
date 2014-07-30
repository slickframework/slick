<?php

/**
 * Schema loader test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Schema;

use Codeception\Util\Stub;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Schema\Loader;

/**
 * Schema loader test case
 *
 * @package   Test\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class LoaderTest extends \Codeception\TestCase\Test
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Sets default adapter for tests
     */
    protected function _before()
    {
        $this->_adapter = Stub::make(
            '\Slick\Database\Adapter\MysqlAdapter',
            [
                'autoConnect' => false,
                'database' => 'schemaTest'
            ]
        );
    }

    /**
     * Clear for next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Create a mysql schema object
     * @test
     */
    public function createSchema()
    {
        $loader = new Loader(['adapter' => $this->_adapter]);
        $this->assertInstanceOf('Slick\Database\Schema\Loader', $loader);

        $schema = $loader->load();
        $this->assertInstanceOf('Slick\Database\Schema\SchemaInterface', $schema);
    }

    /**
     * Load invalid loader class
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function loadInvalidClass()
    {
        $loader = new Loader([
            'adapter' => $this->_adapter,
            'class' => 'Database\Schema\MyLoader'
        ]);
        $loader->load();
    }

    /**
     * Load undefined loader class
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function loadUndefinedClass()
    {
        $loader = new Loader([
            'adapter' => $this->_adapter,
            'class' => 'Database\Schema\other'
        ]);
        $loader->load();
    }

    /**
     * Load undefined loader class
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function loadWithoutAdapter()
    {
        $loader = new Loader([
            'class' => 'Database\Schema\other'
        ]);
        $loader->load();
    }
}

/**
 * Mocked fake loader
 *
 * @package Database\Schema
 */
class MyLoader
{
    public function __construct($options)
    {

    }
}
