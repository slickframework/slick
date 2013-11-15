<?php
namespace Database\Connector;
use Codeception\Util\Stub;
use Slick\Database\Database;

class MysqlTest extends \Codeception\TestCase\Test
{


    protected $_connector = null;

    protected function _before()
    {
        parent::_before();
        $db = new Database(
            array(
                'type' => 'mysql',
                'options' => array(
                    'username' => 'test',
                    'password' => '',
                    'hostname' => 'localhost',
                    'database' => 'test'
                )
            )
        );
        $this->_connector = $db->initialize();
        unset($db);
    }

    protected function _after()
    {
        //$this->_connector->disconnect();
        unset($this->_connector);
        parent::_after();
    }

    /**
     * Check the class construction
     * @test
     */
    public function checkClassConstruciton()
    {
        $this->assertInstanceOf('Slick\Database\Connector\ConnectorInterface', $this->_connector);
    }

    /**
     * Try to connecto to database
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     */
    public function connectoToDatabase()
    {
        $this->_connector->dboClass = 'Database\Connector\FakePDO';
        $mysql = $this->_connector->connect();
        $this->assertInstanceOf('Slick\Database\Connector\Mysql', $mysql);
        $mysql->disconnect();

        $this->_connector->dboClass = 'Database\Connector\FakePDOFail';
        $mysql->setPassword("wrong")->connect();
    }

    /**
     * Get lest insert id
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid database service.
     */
    public function getLastInsertId()
    {
        $pdo = new FakePDO('fake');
        $this->_connector->dataObject = $pdo;
        $this->_connector->connected = true;
        $this->assertEquals(23, $this->_connector->getLastInsertId());
        $this->_connector->connected = false;
        $this->_connector->getLastInsertId();
    }

    /**
     * get affected rows
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid database service.
     */
    public function getAffectedRows()
    {
        $this->_connector->dataObject = new FakePDO('fake');
        $this->_connector->connected = true;
        $this->assertEquals(0, $this->_connector->getAffectedRows());
        $this->_connector->lastStatement = new FakeStatement();
        $this->assertEquals(10, $this->_connector->getAffectedRows());
        $this->_connector->connected = false;
        $this->_connector->getAffectedRows();
    }

    /**
     * get affected rows
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     * @expectedExceptionMessage Not connected to a valid database service.
     */
    public function getLastError()
    {
        $this->_connector->dataObject = new FakePDO('fake');
        $this->_connector->connected = true;
        $this->assertEquals("Error message", $this->_connector->getLastError());
        $this->_connector->connected = false;
        $this->_connector->getLastError();
    }

}

class FakePDO extends \PDO
{
    public function __construct($dsn, $user='', $pass='')
    {

    }

    public function lastInsertId()
    {
        return 23;
    }

    public function errorInfo()
    {
        return array(
            2 => "Error message"
        );
    }

}

class FakePDOFail extends FakePDO
{
    public function __construct($dsn, $user='', $pass='')
    {
        throw new \PDOException("Connection error");
    }
}

class FakeStatement
{
    public function rowCount()
    {
        return 10;
    }
}