<?php

/**
 * Database test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database;

use Codeception\Util\Stub;
use Slick\Database\Connector\Mysql;
use Slick\Database\Database;

/**
 * Database test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DatabaseTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Database
     */
    protected $_database;

    /**
     * Sets the SUT object
     */
    protected function _before()
    {
        parent::_before();
        $this->_database = new Database();
    }

    /**
     * Clean up for next test
     */
    protected function _after()
    {
        $this->_database = null;
        parent::_after();
    }

    /**
     * Initialize a Mysql connector
     * @test
     */
    public function initializeMysqlConnector()
    {
        $this->_database->setType('mysql');
        $db = $this->_database->initialize();
        $this->assertInstanceOf('Slick\Database\Connector\Mysql', $db);
        $db = $db->initialize();
        $this->assertInstanceOf('Slick\Database\Connector\Mysql', $db);
    }

    /**
     * Initialize a SQLite connector
     * @test
     */
    public function initializeSQLiteConnector()
    {
        $this->_database->setType('sqlite');
        $db = $this->_database->initialize();
        $this->assertInstanceOf('Slick\Database\Connector\SQLite', $db);
    }

    /**
     * Initialize a invalid connector
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     * @expectedExceptionMessage Trying to initialize a database connector with an undefined connector type.
     */
    public function initializeInvalidConnector()
    {
        $this->_database->setType(null);
        $db = $this->_database->initialize();

    }

    /**
     * Initialize a unknown connector
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     * @expectedExceptionMessage Trying to initialize a database connector with an unknown connector type.
     */
    public function initializeUnknownConnector()
    {
        $this->_database->setType('MyOtherSql');
        $db = $this->_database->initialize();

    }

    /**
     * Initialize a custom class
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function initializeCustomClass()
    {
        $db = new Database(['type' => '\Database\MyOwnConnector']);
        $db = $db->initialize();
        $this->assertInstanceOf('Database\MyOwnConnector', $db);

        $db = new Database(['type' => '\Database\MyFooConnector']);
        $db->initialize();
    }

}

class MyOwnConnector extends Mysql
{

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar SingletonInterface $instance The *Singleton* instances
     *  of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return \Slick\Database\Connector\Mysql The *Singleton* instance.
     */
    public static function getInstance($options = array())
    {
        static $instance;

        if (is_null($instance)) {
            $instance = array();
        }

        $key = md5(serialize($options));

        if (
            !isset($instance[$key]) ||
            !is_a(
                $instance[$key],
                'Slick\Database\Connector\ConnectorInterface'
            )
        ) {
            $instance[$key] = new MyOwnConnector($options);
        }
        return $instance[$key];
    }


}

class MyFooConnector
{
    public static function getInstance($options = array())
    {
        return new MyFooConnector();
    }
}