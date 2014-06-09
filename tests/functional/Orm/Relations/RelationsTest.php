<?php

/**
 * Database relations test case (MySQL)
 *
 * @package   Test\Orm\Relations
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm\Relations;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Orm\Entity;

/**
 * Database relations test case (MySQL)
 *
 * @package   Test\Orm\Relations
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RelationsTest extends \Codeception\TestCase\Test
{
    /**
     * @var \TestGuy
     */
    protected $testGuy;

    /**
     * Retrieve a post and check the relations
     * @test
     */
    public function retrievePost()
    {
        Configuration::addPath(__DIR__);
        $post = Post::first();
        $this->assertInstanceOf('\Orm\Relations\Post', $post);
        $this->assertEquals('My blog post', $post->title);
        /** @var Comment[] $comments */
        $comments = $post->comments;
        $this->assertEquals(2, count($comments));
        $this->assertInstanceOf('\Slick\Database\RecordList', $comments);
        $this->assertInstanceOf('\Orm\Relations\User', $comments[0]->user);
        $user = $comments[0]->user;
        if ($user->id == 1) {
            $this->assertEquals('silvam.filipe@gmail.com', $user->email);
            $this->assertEquals('Filipe Silva', $user->profile->fullName);
        } else {
            $this->assertEquals('some.name@example.com', $user->email);
            $this->assertEquals('Guest User', $user->profile->fullName);
        }
        $this->assertInstanceOf('\Orm\Relations\Post', $comments[0]->post);
        $this->assertInstanceOf('\Orm\Relations\User', $comments[0]->user->profile->user);

        $user = User::get(1);
        $name = $user->profile->user->name;
        $this->assertEquals('fsilva', $name);
    }

    /**
     * Getting the correct field value on results field mame conflict
     * @test
     */
    public function getDataOnFieldNameConflict()
    {
        $comment = Comment::get(1);
        $this->assertEquals('This blog is great', $comment->body);
        $this->assertEquals('This is the body for my blog post.', $comment->post->body);
        $this->assertEquals(2, $comment->user->id);
    }

    /**
     * HABTM test
     * @test
     */
    public function checkHasAndBelongsToMany()
    {
        $post = Post::get(1);
        $this->assertInstanceOf('Slick\Database\RecordList', $post->tags);
        $tag = $post->tags[0];
        $this->assertEquals('PHP', $tag->name);
    }

    /**
     * Trying to save relationship data
     * @test
     */
    public function saveBelongsToData()
    {
        $comment = new Comment(['body' => "Good one", 'post' => 1]);
        $this->assertTrue($comment->save());
        $comment = Comment::get($comment->getConnector()->getLastInsertId());
        $this->assertInstanceOf('Orm\Relations\Post', $comment->post);

        $this->assertEquals('1', $comment->post->id);

        $post = Post::get(1);
        $user = User::get(2);

        $other = new Comment(['body' => "other one", 'post' => $post, 'user' => $user]);
        $this->assertTrue($other->save());
        $otherId = $other->getConnector()->getLastInsertId();
        $other = Comment::get($otherId);
        $this->assertTrue($other->save(['user_id' => ['id' => 1]]));
        $other->load();
        $this->assertEquals(1, $other->user->id);

    }

}

class User extends Entity
{

    /**
     * @readwrite
     * @column type=int, size=big, unsigned, primary
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=tiny
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @column type=text, length=255
     * @var string
     */
    protected $_email;

    /**
     * @readwrite
     * @hasMany \Orm\Relations\Comment
     * @var Comment[]
     */
    protected $_comments;

    /**
     * @readwrite
     * @hasOne \Orm\Relations\Profile
     * @var Profile
     */
    protected $_profile;

    /**
     * @read
     * @var string The configuration file name
     */
    protected $_configFile = 'config_orm';

    /**
     * @readwrite
     * @var string
     */
    protected $_table = 'user';

}

class Comment extends Entity
{
    /**
     * @readwrite
     * @column type=int, size=big, unsigned, primary
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=big
     * @var string
     */
    protected $_body;

    /**
     * @readwrite
     * @belongsTo \Orm\Relations\User
     * @var User
     */
    protected $_user;

    /**
     * @readwrite
     * @belongsTo \Orm\Relations\Post
     * @var Post
     */
    protected $_post;

    /**
     * @read
     * @var string The configuration file name
     */
    protected $_configFile = 'config_orm';
}

class Post extends Entity
{

    /**
     * @readwrite
     * @column type=int, size=big, unsigned, primary
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=tiny
     * @var string
     */
    protected $_title;

    /**
     * @readwrite
     * @column type=text, size=big
     * @var string
     */
    protected $_body;

    /**
     * @readwrite
     * @hasMany \Orm\Relations\Comment
     * @var Comment[]
     */
    protected $_comments;

    /**
     * @read
     * @var string The configuration file name
     */
    protected $_configFile = 'config_orm';

    /**
     * @readwrite
     * @hasAndBelongsToMany Orm\Relations\Tag, joinTable=post_tags
     * @var Tag[]
     */
    protected $_tags;
}

class Profile extends Entity
{

    /**
     * @readwrite
     * @column type=int, size=big, unsigned, primary
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=tiny
     * @var string
     */
    protected $_fullName;

    /**
     * @readwrite
     * @belongsTo \Orm\Relations\User
     * @var User
     */
    protected $_user;

    /**
     * @read
     * @var string The configuration file name
     */
    protected $_configFile = 'config_orm';
}

class Tag extends Entity
{

    /**
     * @readwrite
     * @column type=int, size=big, unsigned, primary
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, size=tiny
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @hasAndBelongsToMany Orm\Relations\Post, joinTable=post_tags
     * @var Post[]
     */
    protected $_posts;

    /**
     * @read
     * @var string The configuration file name
     */
    protected $_configFile = 'config_orm';
}