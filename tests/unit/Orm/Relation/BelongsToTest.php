<?php

/**
 * Belongs To relation test case
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
use Slick\Orm\Relation\BelongsTo;

/**
 * Belongs To relation test case
 *
 * @package   Test\Session
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
     * @BelongsTo Orm\Relation\BarBt
     * @var BarBt
     */
    protected $_bar;
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
     * @HasOne Orm\Relation\FooBt
     * @var BarBt
     */
    protected $_foo;
}