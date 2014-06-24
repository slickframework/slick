<?php

/**
 * Database adapter test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database;

use Slick\Database\Exception;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Adapter\AbstractAdapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Exception\ServiceException;
use PDO;

/**
 * Database adapter test case
 *
 * @package   Test\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AdapterTest extends \Codeception\TestCase\Test
{
    /**
     * The SUT object
     * @var AbstractAdapter
     */
    protected $adapter;

    /**
     * Prepare for tests
     */
    protected function _before()
    {
        parent::_before();
        $this->adapter = new MyAdapter();
        $handler = new Handler('Test');
        $this->adapter->handler = $handler;
    }

    /**
     * Clean for next test
     */
    protected function _after()
    {
        unset($this->adapter);
        parent::_after();
    }

    /**
     * Try to create an adapter
     * @test
     */
    public function createAnAdapter()
    {
        $adapter = new MyAdapter();
        $handler = new Handler('Test');
        $adapter->handler = $handler;
        $this->assertSame($handler, $adapter->getHandler());
        $this->assertTrue($adapter->connected);
        $this->assertInstanceOf('Slick\Database\Adapter\AbstractAdapter', $adapter);
        $this->assertTrue($adapter->isConnected());

        $object = $adapter->disconnect();
        $this->assertFalse($adapter->connected);
        $this->assertFalse($adapter->isConnected());
        $this->assertInstanceOf('Database\MyAdapter', $object);

        $this->assertSame($object, $adapter->initialize());
    }

    /**
     * Trying to execute a query
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function executeAQuery()
    {
        Statement::$rowCount = 8;
        $result = $this->adapter->execute("Test sql");
        $this->assertEquals(8, $result);
        $this->assertEquals(8, $this->adapter->getAffectedRows());
        $this->assertEquals(Handler::$lastSql, "Test sql");

        Statement::$rowCount = 5;
        $sql = new CustomSql();
        $sql->queryString = "Query test";
        $result = $this->adapter->execute($sql);
        $this->assertEquals(5, $result);
        $this->assertEquals(5, $this->adapter->getAffectedRows());
        $this->assertEquals(Handler::$lastSql, "Query test");

        $sql = new \stdClass();
        try {
            $this->adapter->execute($sql);
            $this->fail("Not an SqlInterface object. This should fail here.");
        } catch (Exception $exp) {
            $this->assertInstanceOf('Slick\Database\Exception\InvalidArgumentException', $exp);
        }

        Statement::$throwException = true;
        try {
            $this->adapter->execute("Test sql");
            $this->fail("Bad sql used. THis should fail here.");
        } catch (Exception $exp) {
            $this->assertInstanceOf('Slick\Database\Exception\SqlQueryException', $exp);
        }
        Statement::$throwException = false;

        $this->adapter->disconnect();
        $this->adapter->execute("Test sql");
    }

    /**
     * Trying to
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function queryData()
    {
        $result = $this->adapter->query("Query test");
        $this->assertInstanceOf('Slick\Database\RecordList', $result);

        $sql = new CustomSql();
        $sql->queryString = "Query test";
        $result = $this->adapter->query($sql);
        $this->assertInstanceOf('Slick\Database\RecordList', $result);

        $sql = new \stdClass();
        try {
            $this->adapter->query($sql);
            $this->fail("Not an SqlInterface object. This should fail here.");
        } catch (Exception $exp) {
            $this->assertInstanceOf('Slick\Database\Exception\InvalidArgumentException', $exp);
        }

        Statement::$throwException = true;
        try {
            $this->adapter->query("Test sql");
            $this->fail("Bad sql used. THis should fail here.");
        } catch (Exception $exp) {
            $this->assertInstanceOf('Slick\Database\Exception\SqlQueryException', $exp);
        }
        Statement::$throwException = false;

        $this->adapter->disconnect();
        $this->adapter->query("Test sql");
    }

    /**
     * Trying to get the last inserted ID
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function getLastInsertedId()
    {
        $this->assertEquals(2, $this->adapter->getLastInsertId());
        $this->adapter->disconnect();
        $this->adapter->getLastInsertId();
    }
}

/**
 * Class MyAdapter
 * @package Database
 */
class MyAdapter extends AbstractAdapter
{

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function connect()
    {
        $this->_connected = true;
        return $this;
    }
}

/**
 * Class Handler for database adapter tests
 *
 * @package Database
 */
class Handler extends PDO
{
    /**
     * @var bool
     */
    public static $connectionError = false;

    /**
     * @var null|string
     */
    public static $lastSql = null;

    public static $lastId = 2;

    /**
     * @param $dsn
     * @throws \PDOException
     */
    public function __construct($dsn)
    {
        if (static::$connectionError) {
            throw new \PDOException("Error connecting to database.");
        }

        parent::__construct('sqlite::memory:');
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('Database\Statement', array($this)));
    }


    public function prepare($statement, $options = null)
    {
        static::$lastSql = $statement;
        return new Statement($this);
    }

    public function lastInsertId($seqname = null)
    {
        return static::$lastId;
    }
}

class Statement extends \PDOStatement
{

    public static $rowCount = 0;

    public static $params = [];

    public static $throwException  = false;

    public static $rows = [
        [
            'id' => 1,
            'name' => 'test'
        ]
    ];

    public function execute($params = [])
    {
        static::$params = $params;
        if (static::$throwException) {
            throw new \PDOException("Error executing query.");
        }
    }

    public function rowCount()
    {
        return static::$rowCount;
    }

    public function fetchAll($how = null, $class_name = null, $ctor_args = array())
    {
        static::$rowCount = count(static::$rows);
        return static::$rows;
    }
}

class CustomSql implements SqlInterface
{
    public $queryString = null;

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AbstractAdapter $adapter
     * @return SqlInterface
     */
    public function setAdapter(AbstractAdapter $adapter)
    {

    }
}