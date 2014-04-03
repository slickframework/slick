<?php

/**
 * Has Many relation test case
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
use Slick\Orm\Relation\HasMany;

/**
 * Has Many relation test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasManyTest extends \Codeception\TestCase\Test
{

    /**
     * Create entity with belongs to relation
     * @test
     */
    function createHasManyEntity()
    {
        $foo = new FooHm();
        $this->assertInstanceOf('Slick\Orm\EntityInterface', $foo);

        /** @var HasMany $relation */
        $relation = $foo->getRelationsManager()->getRelation('_bar');
        $index = $foo->getRelationsManager()->index;

        $this->assertEquals(['_bar' => 1, '_baz' => 2], $index);
        $this->assertInstanceOf('Slick\Orm\Relation\HasMany', $relation);
        $this->assertInstanceOf('Orm\Relation\FooHm', $relation->getEntity());
        $this->assertInstanceOf('Orm\Relation\BarHm', $relation->getRelated());
        $this->assertEquals(25, $relation->getLimit());
        $this->assertTrue($relation->isDependent());
        $this->assertEquals("foohm_id", $relation->getForeignKey());

        $relation = $foo->getRelationsManager()->getRelation('_baz');
        $this->assertInstanceOf('Slick\Orm\Relation\HasMany', $relation);
        $this->assertInstanceOf('Orm\Relation\FooHm', $relation->getEntity());
        $this->assertInstanceOf('Orm\Relation\BazHm', $relation->getRelated());
        $this->assertEquals(30, $relation->getLimit());
        $this->assertFalse($relation->isDependent());
        $this->assertEquals("barId", $relation->getForeignKey());
    }
}

/**
 * Stub class for tests
 */
class FooHm extends Entity
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
     * @HasMany Orm\Relation\BarHm
     * @var BarBt
     */
    protected $_bar;

    /**
     * @HasMany Orm\Relation\BazHm, limit=30, foreignKey=barId, dependent=0
     * @var BarBt
     */
    protected $_baz;
}

/**
 * Stub class for tests
 */
class BarHm extends Entity
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
     * @BelongsTo \Orm\Relation\FooHm
     * @var BarBt
     */
    protected $_foo;
}

/**
 * Stub class for tests
 */
class BazHm extends Entity
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
     * @BelongsTo \Orm\Relation\FooHm
     * @var BarBt
     */
    protected $_foo;
}