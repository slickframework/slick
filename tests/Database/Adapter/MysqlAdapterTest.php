<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Adapter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\MysqlAdapter;

/**
 * Mysql Adapter Test case
 *
 * @package Slick\Tests\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlAdapterTest extends TestCase
{

    /**
     * @var MysqlAdapter
     */
    protected $adapter;

    /**
     * Creates the adapter without connecting it
     */
    protected function setup()
    {
        parent::setUp();
        $this->adapter = new MysqlAdapter(
            [
                'autoConnect' => false,
                'database' => 'just_a_test',
                'username' => 'dbuser',
                'password' => 'userpass',
                'host' => 'localhost',
                'handleClassName' => 'Slick\Tests\Database\Fixtures\MockPDO'
            ]
        );
    }

    /**
     * Clear all for next test
     */
    protected function tearDown()
    {
        $this->adapter = null;
        parent::tearDown();
    }

    public function testSchemaName()
    {
        $this->assertEquals('just_a_test', $this->adapter->getSchemaName());
    }

    public function testPDOConnection()
    {
        $expected = [
            "mysql:host=localhost;port=3306;dbname=just_a_test;charset=utf8",
            "dbuser",
            "userpass",
            [3 => 2]
        ];
        $this->adapter->connect();
        $this->assertEquals($expected, $this->adapter->handler->arguments);
    }

    public function testConnectionError()
    {
        $this->setExpectedException(
            'Slick\Database\Exception\ServiceException'
        );
        new MysqlAdapter(
            [
                'host' => 'someware',
                'port' => 90922,
                'username' => 'test'
            ]
        );
    }
}
