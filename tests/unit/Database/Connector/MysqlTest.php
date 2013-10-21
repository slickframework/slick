<?php

/**
 * Mysql connector test case
 * 
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Connector;

use Slick\Database\Connector;

/**
 * Mysql connector test case
 * 
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    /**
     * Mysql is a Mysql Connector object.
     * @var \Slick\Database\Connector\Mysql
     */
    protected $mysql;

    /**
     * Creates a Mysql Connector object for tests.
     */
    protected function _before()
    {
        parent::_before();
        $this->mysql = new Connector\Mysql(
            array(
                'host' => 'localhost',
                'username' => 'slick_',
                'schema' => 'dummy_test'
            )
        );

        $service = $this->getMock('\MySQLi');

        $this->mysql->service = $service;
        $this->mysql->connected = true;
    }

    /**
     * Clears all for next test.
     */
    protected function _after()
    {
        unset($this->mysql);
        parent::_after();
    }
    
    /**
     * Check the initializantion of driver
     * @test
     */
    public function inititlizeDriver()
    {
        $this->assertInstanceOf(
            '\Slick\Database\Connector',
            $this->mysql->initialize()
        );
        $this->assertInstanceOf(
            '\Slick\Database\Connector\Mysql',
            $this->mysql->initialize()
        );
    }
    
    /**
     * Test connection to Mysqli
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function connectToMysql()
    {
        $cnn = new Connector\Mysql();
        $this->assertInstanceOf(
            '\Slick\Database\Connector',
            $cnn->connect()
        );
        
        $this->assertInstanceOf(
            '\Slick\Database\Connector',
            $this->mysql->connect()
        );
        $this->assertInstanceOf(
            '\Slick\Database\Connector',
            $this->mysql->disconnect()
        );
        $this->assertFalse($this->mysql->isConnected());
        
        $this->mysql->service = null;
        $this->mysql->connect();
    }
    
    /**
     * Checking scape method.
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid service.
     */
    public function excapeText()
    {
        $this->assertNull($this->mysql->escape(''));
        $this->mysql->disconnect();
        $this->mysql->escape('');
    }
    
    /**
     * Tests the executing query
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid service.
     */
    public function executingQuery()
    {
        $this->assertNull($this->mysql->execute(''));
        $this->mysql->disconnect();
        $this->mysql->execute('');
    }
    
    /**
     * Checking affected rows method.
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid service.
     */
    public function getAffectedRows()
    {
        $this->mysql = new Connector\Mysql();
        $this->mysql->connect();
        $this->assertEquals(0, $this->mysql->getAffectedRows(''));
        $this->mysql->disconnect();
        $this->mysql->getAffectedRows('');
    }
    
    /**
     * Checking last error method.
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid service.
     */
    public function getLastError()
    {
        $this->mysql = new Connector\Mysql();
        $this->mysql->connect();
        $this->assertEquals(null, $this->mysql->getLastError(''));
        $this->mysql->disconnect();
        $this->mysql->getLastError('');
    }
    
    /**
     * Checking last inserted id method.
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid service.
     */
    public function lastInsertedId()
    {
        $this->mysql = new Connector\Mysql();
        $this->mysql->connect();
        $this->assertEquals(0, $this->mysql->getLastInsertId(''));
        $this->mysql->disconnect();
        $this->mysql->getLastInsertId('');
    }

}
