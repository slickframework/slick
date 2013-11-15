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
        $mysql = $this->_connector->connect();
        $this->assertInstanceOf('Slick\Database\Connector\Mysql', $mysql);
        $mysql->disconnect();

        $mysql->setPassword("wrong")->connect();
    }

}