<?php

/**
 * SQLite connector test case
 *
 * @package   Test\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Connector;

use Codeception\Util\Stub;
use Slick\Database\Connector\SQLite;

/**
 * SQLite connector test case
 *
 * @package   Test\Database\Connector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SQLiteTest extends \Codeception\TestCase\Test
{

    /**
     * SQLite conncetor
     * @var \Slick\Database\Connector\SQLite
     */
    protected $_connector = null;

    /**
     * Sets the connector for testing
     * @return [type] [description]
     */
    protected function _before()
    {
        parent::_before();
        $this->_connector = SQLite::getInstance();
        $this->_connector->setFile(":memory:");
    }

    /**
     * Clean up for next test
     * @test
     */
    protected function _after()
    {
        $this->_connector = null;
        parent::_after();
    }

    /**
     * Connecting to database file
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     */
    public function connectToDatabase()
    {
        $db = $this->_connector->connect();
        $this->assertInstanceOf('Slick\Database\Connector\SQLite', $db);
        $this->assertInstanceOf('Slick\Database\Connector\SQLite', $db->disconnect());
        $db->setFile("/some %strange path(/");
        $db = $db->connect();
    }

    /**
     * Retrive a valid query
     * @test
     */
    public function retrieveQuery()
    {
        $db = $this->_connector->connect();
        $query = $db->query("SELECT * FROM table");
        $this->assertInstanceOf('Slick\Database\Query\Query', $query);
        $this->assertEquals('SQLite', $query->dialect);
        $this->assertEquals("SELECT * FROM table", $query->sql);
        $this->assertSame($db, $query->connector);
    }

}