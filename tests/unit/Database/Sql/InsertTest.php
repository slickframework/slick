<?php

/**
 * Insert SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Sql;
use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;

/**
 * Class InsertTest
 * @package Database\Sql
 */
class InsertTest extends \Codeception\TestCase\Test
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Prepares for test
     */
    protected function _before()
    {
        parent::_before();
        $this->_adapter = new Adapter(
            [
                'driver' => '\Database\Sql\InsertAdapter'
            ]
        );
        $this->_adapter = $this->_adapter->initialize();
    }

    /**
     * Cleans for next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Crate and parse an insert sql object
     * @test
     */
    public function parseInsertStatement()
    {
        $sql = Sql::createSql($this->_adapter)->insert('users');
        $sql->set(
            [
                'name' => 'filipe',
                'email' => 'filipe@example.com'
            ]
        );
        $expected = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals(
            [
                ':name' => 'filipe',
                ':email' => 'filipe@example.com'
            ],
            $sql->getParameters()
        );

        $this->assertEquals(1, $sql->execute());
        $this->assertEquals($sql, InsertAdapter::$sql);
        $this->assertEquals($sql->getParameters(), InsertAdapter::$params);
    }

}

/**
 * Mock class for test execute methods
 */
class InsertHandle extends \PDO
{
    /**
     * PDO override
     */
    public function __construct()
    {
        parent::__construct('sqlite::memory:');
    }
}

/**
 * Mock the adapter
 */
class InsertAdapter extends Adapter\AbstractAdapter implements AdapterInterface
{

    public static $sql;

    public static $params;

    /**
     * @write
     * @var string
     */
    protected $_handlerClass = '\Database\Sql\InsertHandle';

    /**
     * Connects to the database service
     *
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function connect()
    {
        $class = $this->_handlerClass;
        $this->_handler = new $class();
        $this->_connected = true;
    }

    /**
     * Overrides for tests
     *
     * @param Sql\SqlInterface|string $sql
     * @param array $parameters
     * @return int|void
     */
    public function execute($sql, $parameters = [])
    {
        static::$sql = $sql;
        static::$params = $parameters;
        return 1;
    }

    /**
     * Returns the schema name for this adapter
     *
     * @return string
     */
    public function getSchemaName()
    {
        // TODO: Implement getSchemaName() method.
    }

}