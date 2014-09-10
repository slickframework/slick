<?php

/**
 * Relation test case
 *
 * @package   Test\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm\Relation;
use Slick\Orm\Entity;
use Slick\Orm\Relation\HasMany;

/**
 * Relation test case
 *
 * @package   Test\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RelationTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Guesses the foreignKey
     * @test
     */
    public function guessForeignKey()
    {
        $post = new Post();
        $tag = new Tag();
        $belongsTo = Entity\Manager::getInstance()->get($post)
            ->getRelation('_tag');
        $this->assertEquals('tag_id', $belongsTo->getForeignKey());
        $hasMany = Entity\Manager::getInstance()->get($tag)
            ->getRelation('_posts');
        $this->assertEquals('tag_id', $hasMany->getForeignKey());
        $hasOne = Entity\Manager::getInstance()->get($post)
            ->getRelation('_profile');
        $this->assertEquals('post_id', $hasOne->getForeignKey());
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
     * @hasMany Orm\Relation\Post
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
    /**
     * @readwrite
     * @column
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @belongsTo Orm\Relation\Tag
     * @var Tag
     */
    protected $_tag;

    /**
     * @readwrite
     * @hasOne Orm\Relation\Profile
     * @var Profile
     */
    protected $_profile;
}

/**
 * Class Profile
 * @package Orm\Relation
 */
class Profile extends Entity
{
    /**
     * @readwrite
     * @column
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @belongsTo Orm\Relation\Post
     * @var Tag
     */
    protected $_post;
}