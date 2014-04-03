<?php

/**
 * Entity test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm;

use Codeception\TestCase\Test;
use Codeception\Util\Stub;
use Database\MyOwnConnector;
use Slick\Configuration\Configuration;
use Slick\Database\Connector\SQLite;
use Slick\Database\Query\Query;
use Slick\Di\DependencyInjector;
use Slick\Orm\Entity;

/**
 * Entity test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntityTest extends Test
{

    /**
     * @var User
     */
    protected $_user;

    /**
     * Prepare entity
     */
    protected function _before()
    {
        parent::_before();
        Configuration::addPath(__DIR__);
        $connector = MyTestConnector::getInstance();
        $di = new DependencyInjector();
        $di->set('db_default', $connector);
        $this->_user = new User();
        $this->_user->setDi($di);
    }

    /**
     * clean up after each test
     */
    protected function _after()
    {
        unset($this->_user);
        parent::_after();
    }

    /**
     * Crate an entity and check default values
     * @test
     * @expectedException \Slick\Orm\Exception\PrimaryKeyException
     */
    public function crateEntity()
    {
        $this->assertEquals('User', $this->_user->getAlias());
        $this->assertEquals('users', $this->_user->getTable());
        $this->assertEquals('id', $this->_user->primaryKey);
        $this->assertInstanceOf('Slick\Database\Connector\SQLite', $this->_user->connector);
        $this->assertInstanceOf('Slick\Database\Query\QueryInterface', $this->_user->query());
        $myUser = new User();
        $di = DependencyInjector::getDefault();
        $this->assertSame($di, $myUser->getDi());
        new Post();
    }

    /**
     * Gets the column definition for this entity
     * @test
     */
    public function getColumnDefinition()
    {
        $columns = $this->_user->getColumns();
        $this->assertTrue($columns->hasColumn('id'));
        $this->assertTrue($columns->hasColumn('name'));
        $this->assertTrue($columns['id']->primaryKey);
        $this->assertEquals('int', $columns['id']->type);
        $this->assertEquals('big', $columns['id']->size);
        $this->assertTrue($columns['id']->unsigned);
        $this->assertEquals('_id', $columns['id']->raw);
        $this->assertFalse($columns['id']->index);

        $this->assertEquals('required' ,$columns['name']->validate->value);
        $this->assertEquals('text' ,$columns['name']->type);


    }

    /**
     * Retrieves the entity by providing the primary key id
     * @test
     */
    public function getEntityById()
    {
        MyStatement::$isEmpty = false;
        $user = User::get(1);
        $this->assertInstanceOf('\Orm\User', $user);
        $this->assertEquals('Jon Doe', $user->name);
        MyStatement::$isEmpty = true;
        $nullUser = User::get(9);
        $this->assertNull($nullUser);
    }

    /**
     * Run count on table
     * @test
     */
    public function getEntityCount()
    {
        MyStatement::$isEmpty = false;
        MyTestConnector::$isCount = true;
        $rows = User::count();
        MyTestConnector::$isCount = false;
        $this->assertEquals(
            'SELECT COUNT(*) AS totalRows FROM users',
            MyTestConnector::$lastSql
        );
        $this->assertEquals(5, $rows);
    }

    /**
     * Retrieve the first element of a query
     * @test
     */
    public function getFirstEntity()
    {
        $sqlGetFirst = <<<sql
SELECT name, ver FROM users
WHERE name LIKE ?
ORDER BY name DESC
LIMIT 1
sql;
        MyStatement::$isEmpty = false;
        $user = User::first(
            [
                'conditions' => ['name LIKE ?' => '%on%'],
                'fields' => ['name', 'ver'],
                'order' => 'name DESC'
            ]
        );
        $this->assertInstanceOf('\Orm\User', $user);
        $this->assertEquals('Jon Doe', $user->name);
        $this->assertEquals($sqlGetFirst, MyTestConnector::$lastSql);

        MyStatement::$isEmpty = true;
        $nullUser = User::first();
        $this->assertNull($nullUser);

    }

    /**
     * Retrieves all data from an entity
     * @test
     */
    public function retrieveAllRows()
    {
        MyStatement::$isEmpty = false;
        /** @var User[] $users */
       $users = User::all(
            [
                'conditions' => ['name LIKE ?' => '%on%'],
                'fields' => ['name', 'ver'],
                'order' => 'name DESC',
                'limit' => 10
            ]
        );

        $this->assertInstanceOf('Slick\Database\RecordList', $users);
        $this->assertTrue(count($users) > 0);
        $this->assertInstanceOf('Orm\User', $users[0]);
        $this->assertEquals('Ane Doe', $users[1]->name);
    }

    /**
     * Retrieve an empty record list
     * @test
     */
    public function getEmptyRecordList()
    {
        MyStatement::$isEmpty = true;
        $emptyList = User::all();
        $this->assertInstanceOf('Slick\Database\RecordList', $emptyList);
        $this->assertFalse(count($emptyList) > 0);
    }

    /**
     * Use load method of entity
     * @test
     * @expectedException \Slick\Orm\Exception\PrimaryKeyException
     */
    public function loadRow()
    {
        MyStatement::$isEmpty = false;
        $user = new User();
        $user->setId(1)->load();
        $this->assertInstanceOf('\Orm\User', $user);
        $this->assertEquals('Jon Doe', $user->name);

        $invalid = new User();
        $invalid->load();

    }

    /**
     * Delete a single row
     * @test
     * @expectedException \Slick\Orm\Exception\PrimaryKeyException
     */
    public function deleteRow()
    {
        MyStatement::$isEmpty = false;
        $user = User::get(1);
        MyStatement::$isEmpty = true;
        $this->assertTrue($user->delete());
        $sql = <<<SQL
DELETE FROM users
WHERE id = ?
SQL;
        $this->assertEquals($sql, MyTestConnector::$lastSql);
        $emptyUser = new User();
        $emptyUser->delete();
    }

    /**
     * Save data (insert)
     * @test
     */
    public function saveDataInsert()
    {
        $data = ['name' => 'test user'];
        $user = new User();
        $user->save();
        $insertUser = <<<SQL
INSERT INTO users (`id`, `name`)
VALUES (:id, :name)
SQL;
        $this->assertEquals($insertUser, MyTestConnector::$lastSql);

        $this->assertTrue($user->save(['name' => 'other user', 'ver' => 1]));

        $insertUser = <<<SQL
INSERT INTO users (`name`, `ver`)
VALUES (:name, :ver)
SQL;
        $this->assertEquals($insertUser, MyTestConnector::$lastSql);
    }

    /**
     * Save data (update)
     * @test
     */
    public function saveDataUpdate()
    {
        MyStatement::$isEmpty = false;
        $user = User::get(1);
        MyStatement::$isEmpty = true;
        $user->name = 'other';
        $this->assertTrue($user->save());
        $updated = <<<SQL
UPDATE users SET `name`=:name
WHERE id = :id
SQL;
        $this->assertEquals($updated, MyTestConnector::$lastSql);

        $this->assertTrue($user->save(['id' => 1, 'var' => 'Updated name']));

        $updated = <<<SQL
UPDATE users SET `var`=:var
WHERE id = :id
SQL;
        $this->assertEquals($updated, MyTestConnector::$lastSql);
    }

}




/**
 * Class User
 * @package Orm
 */
class User extends Entity
{

    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, length=255
     * @validate required
     * @var string
     */
    protected $_name;


}

class Post extends Entity
{
    /**
     * @readwrite
     * @column type=text, length=255
     * @validate required
     * @var string
     */
    protected $_name;
}

/**
 * Mock connector for entity test
 */
class MyTestConnector extends SQLite
{

    public static $lastSql = null;

    public static $isCount = false;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar SingletonInterface $instance The *Singleton* instances
     *  of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return \Slick\Database\Connector\SQLite The *Singleton* instance.
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
            $instance[$key] = new MyTestConnector($options);
        }
        return $instance[$key];
    }

    public function query($sql = null)
    {
        return new MyTestQuery(
            array(
                'dialect' => 'SQLite',
                'connector' => $this,
                'sql' => $sql
            )
        );
    }

    public static $result = 'all';

    public static $resultSet = [
        [
            'id' => '1',
            'name' => 'Jon Doe',
            'ver' => '2'
        ],
        [
            'id' => '2',
            'name' => 'Ane Doe',
            'ver' => '2'
        ]
    ];

    public function execute($sql)
    {

        return self::$result;
    }

    public function prepare($sql)
    {
        self::$lastSql = $sql;
        return new MyStatement();
    }

}

/**
 * Mock query for entity test
 */
class MyTestQuery extends Query
{

}

class MyStatement
{
    public static $isEmpty = false;

    public function execute($param = [])
    {
        return true;
    }

    public function count()
    {
        if (self::$isEmpty)
            return 0;
        return 2;
    }

    public function columnCount()
    {
        if (self::$isEmpty)
            return 0;
        return 2;
    }

    public function fetchAll($mode = 0)
    {
        if (self::$isEmpty)
            return [];
        if (MyTestConnector::$isCount) {
            return [['totalRows' => 5]];
        }
        return MyTestConnector::$resultSet;
    }
}