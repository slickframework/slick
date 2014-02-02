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
     * @HasOne \Orm\Relation\Bar
     * @var Bar
     */
    protected $_bar;

    /**
     * @HasOne \Orm\Relation\Baz, dependent=0, type=inner, foreignKey=fooId
     * @var Baz
     */
    protected $_baz;
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