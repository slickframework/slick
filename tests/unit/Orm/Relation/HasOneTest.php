<?php

/**
 * HasOne relation test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm\Relation;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Database\Connector\SQLite;
use Slick\Database\Query\Query;
use Slick\Orm\Entity;
use Slick\Orm\Relation\HasOne;

/**
 * HasOne relation test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasOneTest extends \Codeception\TestCase\Test
{

    /**
     * Crate an entity with a HasOne relation
     * @test
     */
    public function createEntityWithHasOneRelation()
    {
        $foo = new Foo();
        $this->assertInstanceOf('Slick\Orm\EntityInterface', $foo);
        /** @var HasOne $relation */
        $relation = $foo->getRelationsManager()->getRelation('_bar');
        $index = $foo->getRelationsManager()->index;

        $this->assertEquals(['_bar' => 1, '_baz' => 2], $index);

        $this->assertInstanceOf('Slick\Orm\Relation\HasOne', $relation);
        $this->assertInstanceOf('Orm\Relation\Foo', $relation->getEntity());
        $this->assertInstanceOf('Orm\Relation\Bar', $relation->getRelated());
        $this->assertEquals('LEFT', $relation->getType());
        $this->assertTrue($relation->isDependent());
        $this->assertEquals("foo_id", $relation->getForeignKey());

        $relation = $foo->getRelationsManager()->getRelation('_baz');
        $this->assertInstanceOf('Slick\Orm\Relation\HasOne', $relation);
        $this->assertInstanceOf('Orm\Relation\Foo', $relation->getEntity());
        $this->assertInstanceOf('Orm\Relation\Baz', $relation->getRelated());
        $this->assertEquals('INNER', $relation->getType());
        $this->assertFalse($relation->isDependent());
        $this->assertEquals("fooId", $relation->getForeignKey());

    }

    /**
     * Create an undefined class
     * @test
     * @expectedException \Slick\Orm\Exception\UndefinedClassException
     */
    public function createUndefinedEntity()
    {
        new FooUndefined();
    }

    /**
     * Create an undefined class
     * @test
     * @expectedException \Slick\Orm\Exception\InvalidArgumentException
     */
    public function createNotAnEntityRelation()
    {
        new BarNotEntity();
    }

    /**
     * Check that the event before find and the join sql is in place
     * @test
     */
    public function addJoinInfo()
    {
        Configuration::addPath(dirname(__DIR__));
        $foo = Foo::get(1);
        $sql = <<<SQL
SELECT foos.*, bars.*, bazs.* FROM foos
LEFT JOIN bars ON bars.foo_id = foos.id
INNER JOIN bazs ON bazs.fooId = foos.id
WHERE foos.id = ?
LIMIT 1
SQL;
        $this->assertEquals($sql, MyTestConnector::$lastSql);
        $this->assertEquals('Foo name', $foo->name);
        $this->assertEquals('1', $foo->id);
        $this->assertEquals('1', $foo->bar->id);

    }

}

/**
 * Stub class for test
 */
class Foo extends Entity
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
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @HasOne \Orm\Relation\Bar
     * @var Bar
     */
    protected $_bar;

    /**
     * @readwrite
     * @HasOne \Orm\Relation\Baz, dependent=0, type=inner, foreignKey=fooId
     * @var Baz
     */
    protected $_baz;

    /**
     * @read
     * @var string The data source to use
     */
    protected $_dataSourceName = "relation";
}

/**
 * Stub class for tests
 */
class Bar extends Entity
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
     * @var string
     */
    protected $_name;

    /**
     * @read
     * @var string The data source to use
     */
    protected $_dataSourceName = "relation";
}

/**
 * Stub class for tests
 */
class Baz extends Entity
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
     * @var string
     */
    protected $_name;

    /**
     * @read
     * @var string The data source to use
     */
    protected $_dataSourceName = "relation";
}

/**
 * Stub class for tests
 */
class FooUndefined extends Entity
{
    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @HasOne \Unknown\Name\Space\Class
     * @var mixed
     */
    protected $_foo;


}

/**
 * Stub class for tests
 */
class BarNotEntity extends Entity
{
    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @HasOne \StdClass
     * @var mixed
     */
    protected $_foo;

}

/**
 * Mock connector for entity test
 */
class MyTestConnector extends SQLite
{

    public static $lastSql = null;

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

    public static $resultSet = array(
        array(
            'id' => array(
                '0' => 1,
                '1' => 1,
                '2' => 1
            ),
            'name' => array(
                '0' => 'Foo name',
                '1' => 'Bar name',
                '2' => 'Baz name'
            ),
            'foo_id' => 1,
            'fooId' => 1
        )
    );

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
        return MyTestConnector::$resultSet;
    }
}