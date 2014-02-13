<?php

/**
 * Belongs To relation test case
 *
 * @package   Test\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm\Relation;

use Codeception\Util\Stub;
use Slick\Database\Connector\SQLite;
use Slick\Database\Query\Query;
use Slick\Orm\Entity;
use Slick\Orm\Relation\BelongsTo;
use Slick\Configuration\Configuration;

/**
 * Belongs To relation test case
 *
 * @package   Test\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BelongsToTest extends \Codeception\TestCase\Test
{

    /**
     * Create entity with belongs to relation
     * @test
     */
    function createBelongsToEntity()
    {
        $foo = new FooBt();
        $this->assertInstanceOf('Slick\Orm\EntityInterface', $foo);

        /** @var BelongsTo $relation */
        $relation = $foo->getRelationsManager()->getRelation('_bar');
        $index = $foo->getRelationsManager()->index;

        $this->assertEquals(['_bar' => 1], $index);
        $this->assertInstanceOf('Slick\Orm\Relation\BelongsTo', $relation);
        $this->assertInstanceOf('Orm\Relation\FooBt', $relation->getEntity());
        $this->assertInstanceOf('Orm\Relation\BarBt', $relation->getRelated());
        $this->assertEquals('LEFT', $relation->getType());
        $this->assertTrue($relation->isDependent());
        $this->assertEquals("barbt_id", $relation->getForeignKey());
    }

    /**
     * Add join info to query
     * @test
     */
    public function addJoinInfo()
    {
        Configuration::addPath(dirname(__DIR__));
        $foo = FooBt::all(['limit' => '10']);
        $sql = <<<SQL
SELECT foobts.*, barbts.* FROM foobts
LEFT JOIN barbts ON foobts.barbt_id = barbts.id
LIMIT 10
SQL;

        $this->assertEquals($sql, BelongsToConnector::$lastSql);
        $this->assertEquals(2, count($foo));
        $this->assertInstanceOf('Orm\Relation\FooBt', $foo[0]);
        $this->assertEquals('Other Foo name', $foo[1]->name);
        $this->assertInstanceOf('Orm\Relation\BarBt', $foo[0]->bar);
        $this->assertInstanceOf('Orm\Relation\BarBt', $foo[1]->bar);

        $other = FooBt::get(1);
        $this->assertInstanceOf('Orm\Relation\FooBt', $other);
        $this->assertInstanceOf('Orm\Relation\BarBt', $other->bar);
        $this->assertEquals('test', $other->bar->type);

        $first = FooBt::first();
        $this->assertInstanceOf('Orm\Relation\FooBt', $first);
        $this->assertInstanceOf('Orm\Relation\BarBt', $first->bar);
        $this->assertEquals('test', $first->bar->type);
    }
}

/**
 * Stub class for tests
 */
class FooBt extends Entity
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
     * @BelongsTo Orm\Relation\BarBt
     * @var BarBt
     */
    protected $_bar;

    /**
     * @read
     * @var string The data source to use
     */
    protected $_dataSourceName = "belongsTo";
}

/**
 * Stub class for tests
 */
class BarBt extends Entity
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
     * @column type=text
     * @var string
     */
    protected $_type;

    /**
     * @HasOne Orm\Relation\FooBt
     * @var BarBt
     */
    protected $_foo;

    /**
     * @read
     * @var string The data source to use
     */
    protected $_dataSourceName = "belongsTo";
}

/**
 * Mock connector for entity test
 */
class BelongsToConnector extends SQLite
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
            $instance[$key] = new BelongsToConnector($options);
        }
        return $instance[$key];
    }

    public function query($sql = null)
    {
        return new BelongsToQuery(
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
                '1' => 1
            ),
            'name' => array(
                '0' => 'Foo name',
                '1' => 'Bar name'
            ),
            'bar_id' => 1,
            'type' => 'test'
        ),
        array(
            'id' => array(
                '0' => 2,
                '1' => 1
            ),
            'name' => array(
                '0' => 'Other Foo name',
                '1' => 'Bar name'
            ),
            'bar_id' => 1,
            'type' => 'test'
        )
    );

    public function execute($sql)
    {
        return self::$result;
    }

    public function prepare($sql)
    {
        self::$lastSql = $sql;
        return new BelongsToStatement();
    }

}

/**
 * Mock query for entity test
 */
class BelongsToQuery extends Query
{

}

class BelongsToStatement
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
        return BelongsToConnector::$resultSet;
    }
}