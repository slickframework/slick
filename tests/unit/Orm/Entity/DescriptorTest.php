<?php

/**
 * Entity descriptor test case
 *
 * @package   Test\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm\Entity;

use Slick\Orm\Entity;

/**
 * Entity descriptor test case
 *
 * @package   Test\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DescriptorTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Describe an entity
     * @test
     */
    public function describeAnEntity()
    {
        $descriptor = new Entity\Descriptor(['entity' => 'Orm\Entity\Tag']);
        $this->assertInstanceOf('Orm\Entity\Tag', $descriptor->getEntity());
        $relations = $descriptor->getRelations();
        $this->assertEquals($relations, $descriptor->refreshRelations(true));
    }
}

/**
 * Class Tags
 * @package Orm\Entity
 */
class Tag extends Entity
{
    /**
     * @readwrite
     * @column
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @hasMany Orm\Entity\Post
     * @var object[]
     */
    protected $_posts;
}

/**
 * Class Post
 * @package Orm\Entity
 */
class Post extends Entity
{

}