<?php

/**
 * Select SQL test case
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
use Slick\Database\RecordList;
use Slick\Database\Adapter\AdapterInterface;

/**
 * Select SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectTest extends \Codeception\TestCase\Test
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
                'driver' => '\Database\Sql\SelectAdapter'
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
     * Trying to create a basis SQL select statement
     * @test
     */
    public function createBasicSelectStatement()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $this->assertEquals($this->_adapter, $sql->getAdapter());
        $this->assertInstanceOf('Slick\Database\Sql\Select', $sql);
        $expected = "SELECT users.* FROM users";
        $this->assertEquals($expected, $sql->getQueryString());
        $sql->where(['id = :id' => [':id' => 1]]);
        $expected .= " WHERE id = :id";
        $this->assertEquals($expected, $sql->getQueryString());
        $sql->setDistinct(true);
        $expected = "SELECT DISTINCT users.* FROM users";
        $expected .= " WHERE id = :id";
        $this->assertEquals($expected, $sql->getQueryString());

        $sql = Sql::createSql($this->_adapter)->select('users', 'count(*) as total');
        $expected = "SELECT count(*) as total FROM users";
        $this->assertEquals($expected, $sql->getQueryString());

    }

    /**
     * Trying to do a select with joins
     * @test
     */
    public function doSelectWithJoins()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', null);
        $sql->getJoins()[0]->setFields(null);
        $expected = "SELECT users.* FROM users LEFT JOIN roles ON roles.id = users.role_id";
        $this->assertEquals($expected, $sql->getQueryString());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', ['*']);
        $expected = "SELECT users.*, roles.* FROM users LEFT JOIN roles ON roles.id = users.role_id";
        $this->assertEquals($expected, $sql->getQueryString());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', ['*'], 'Role', Sql\Select\Join::JOIN_INNER);
        $expected = "SELECT users.*, Role.* FROM users INNER JOIN roles AS Role ON roles.id = users.role_id";
        $this->assertEquals($expected, $sql->getQueryString());
    }

    /**
     * Do a select with order by
     * @test
     */
    public function selectWithOrderBy()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->order('users.age DESC');
        $expected = "SELECT users.* FROM users ORDER BY users.age DESC";
        $this->assertEquals($expected, $sql->getQueryString());

    }

    /**
     * Tests simple limit
     * @test
     */
    public function selectSimpleLimit()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->limit(10);
        $expected = "SELECT users.* FROM users FETCH FIRST 10 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());
    }

    /**
     * Try to select limited rows with offset
     * @test
     */
    public function selectLimitWithOffset()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->limit(10, 9);
        $expected = "SELECT users.* FROM users OFFSET 9 ROWS FETCH FIRST 10 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());
    }

    /**
     * Trying to verify the where methods trait
     * @test
     */
    public function verifyWhereMethods()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where('active = 1')
            ->andWhere(['name LIKE :name' => [':name' => '%test%']])
            ->orWhere(['active = 0', 'banned = 0']);
        $expected = 'SELECT users.* FROM users WHERE active = 1 AND name LIKE' .
            ' :name OR (active = 0 AND banned = 0)';
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals([':name' => '%test%'], $sql->getParameters());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where(['id = ?' => 1]);
        $expected = 'SELECT users.* FROM users WHERE id = ?' ;
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals([1], $sql->getParameters());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where(['id > ? AND id < ?' => [1, 3]]);
        $expected = 'SELECT users.* FROM users WHERE id > ? AND id < ?';
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals([1, 3], $sql->getParameters());
    }

    /**
     * Trying to retrieve all select SQL rows
     * @test
     */
    public function retrieveAllSelectRecords()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where('active = 1')
            ->andWhere(['name LIKE :name' => [':name' => '%test%']])
            ->orWhere(['active = 0', 'banned = 0']);
        $expected = 'SELECT users.* FROM users WHERE active = 1 AND name LIKE' .
            ' :name OR (active = 0 AND banned = 0)';
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals([':name' => '%test%'], $sql->getParameters());

        $result = $sql->all();
        $this->assertEquals(2, count($result));
        $this->assertEquals('Jon Doe', $result[0]['name']);
        $this->assertInstanceOf('Slick\Database\RecordList', $result);

        $this->assertEquals($sql, SelectAdapter::$sql);
        $this->assertEquals($sql->getParameters(), SelectAdapter::$params);

    }

    /**
     * Trying to retrieve first select SQL rows
     * @test
     */
    public function retrieveFirstSelectRecords()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where('active = 1')
            ->andWhere(['name LIKE :name' => [':name' => '%test%']])
            ->orWhere(['active = 0', 'banned = 0']);
        $expected = 'SELECT users.* FROM users WHERE active = 1 AND name LIKE' .
            ' :name OR (active = 0 AND banned = 0) FETCH FIRST 1 ROWS ONLY';

        SelectAdapter::$isFirst = true;

        $result = $sql->first();
        $this->assertEquals($expected, SelectAdapter::$sql->getQueryString());
        $this->assertEquals(1, count($result));
        $this->assertEquals('Jon Doe', $result[0]['name']);
        $this->assertInstanceOf('Slick\Database\RecordList', $result);

        $this->assertNotEquals($sql, SelectAdapter::$sql);
        $this->assertEquals($sql->getParameters(), SelectAdapter::$params);

        SelectAdapter::$isFirst = false;
    }

    /**
     * Trying to count select SQL rows
     * @test
     */
    public function countSelectSqlObject()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->where('active = 1')
            ->andWhere(['name LIKE :name' => [':name' => '%test%']])
            ->orWhere(['active = 0', 'banned = 0']);
        $sql->join('roles', 'roles.id = users.role_id', ['*']);
        $expected = 'SELECT COUNT(*) AS total FROM users WHERE active = 1 AND name LIKE' .
            ' :name OR (active = 0 AND banned = 0) LEFT JOIN roles ON roles.id = users.role_id';

        SelectAdapter::$isCount = true;
        $result = $sql->count();
        $this->assertEquals($expected, SelectAdapter::$sql->getQueryString());
        $this->assertEquals(120, $result);

        $this->assertNotEquals($sql, SelectAdapter::$sql);
        $this->assertEquals($sql->getParameters(), SelectAdapter::$params);
        SelectAdapter::$isCount = false;
    }
}

/**
 * Mock class for test execute methods
 */
class SelectHandle extends \PDO
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
class SelectAdapter extends Adapter\AbstractAdapter implements AdapterInterface
{

    /**
     * @var Sql\Select
     */
    public static $sql;

    public static $params;

    public static $isFirst = false;

    public static $isCount = false;

    public static $result = [
        [
            'id' => 1,
            'name' => 'Jon Doe'
        ],
        [
            'id' => 2,
            'name' => 'Jane Doe'
        ]
    ];

    public static $countData = [
        [
            'total' => 120
        ]
    ];

    /**
     * @write
     * @var string
     */
    protected $_handlerClass = '\Database\Sql\SelectHandle';

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
    public function query($sql, $parameters = [])
    {
        static::$sql = $sql;
        static::$params = $parameters;

        if (static::$isFirst) {
            $data = [static::$result[0]];
            $result = new RecordList(['data' => $data]);
        } elseif (static::$isCount){
            $result = new RecordList(['data' => static::$countData]);
        } else {
            $result = new RecordList(['data' => static::$result]);
        }
        return $result;

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