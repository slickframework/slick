<?php

/**
 * Has and belongs to many relation test case
 *
 * @package   Test\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm\Relation;

use Codeception\Util\Stub;
use Slick\Orm\Entity;
use Slick\Orm\Relation\HasAndBelongsToMany;

/**
 * Has and belongs to many relation test case
 *
 * @package   Test\Orm\Relation
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasAndBelongsToManyTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Create a HABTM relation object
     * @test
     */
    public function createHabtmRelation()
    {
        $rel = new HasAndBelongsToMany();
        $post = new Post();
        $rel->setEntity($post)
            ->setRelated('\Orm\Relation\Tag');
        $this->assertEquals("posts_tags", $rel->getJoinTable());
        $this->assertEquals("post_id", $rel->getForeignKey());
        $this->assertEquals("tag_id", $rel->getAssociationFk());

        /** @var HasAndBelongsToMany $tags */
        $tags = $post->getRelationsManager()->getRelation('_tags');
        $this->assertInstanceOf('\Slick\Orm\Relation\HasAndBelongsToMany', $tags);
        $this->assertEquals("posts_tags", $tags->getJoinTable());
        $this->assertEquals("post_id", $tags->getForeignKey());
        $this->assertEquals("tag_id", $tags->getAssociationFk());

        $tag = new Tag();
        /** @var HasAndBelongsToMany $posts */
        $posts = $tag->getRelationsManager()->getRelation('_posts');
        $this->assertEquals('PostId', $posts->getAssociationFk());
        $this->assertEquals('postsTags', $posts->getJoinTable());

    }

}

class Post extends Entity
{

    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @hasAndBelongsToMany \Orm\Relation\Tag
     * @var Tag[]
     */
    protected $_tags;

}

class Tag extends Entity
{

    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @HasAndBelongsToMany Orm\Relation\Post, joinTable=postsTags, associationForeignKey=PostId
     * @var Post[]
     */
    protected $_posts;
}