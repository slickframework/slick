<?php

/**
 * Orm HasMany relation test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm\Relation;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Database\Adapter\MysqlAdapter;
use Slick\Database\Schema;
use Slick\Orm\Entity;

/**
 * Orm HasMany relation test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasManyTest extends \Codeception\TestCase\Test
{
    /**
     * @var MysqlAdapter
     */
    protected $_adapter;

    /**
     * @var \TestGuy
     */
    protected $testGuy;

    /**
     * Runs before each test
     */
    protected function _before()
    {
        parent::_before();
        $path = dirname(__DIR__);
        Configuration::addPath($path);
        $cfg = include($path .'/orm-db-settings.php');
        $this->_adapter = new MysqlAdapter($cfg['orm-db']['options']);
        /** @var Schema $schemaDef */
        $schemaDef = include($path . '/schema_def.php');
        $sql = $schemaDef->setAdapter($this->_adapter)->getCreateStatement();
        $this->_adapter->execute($sql);
    }

    /**
     * runs after each test
     */
    protected function _after()
    {
        $this->_adapter->execute('drop table if exists comments;
            drop table if exists posts_tags;
            drop table if exists posts;
            drop table if exists tags;
            drop table if exists profiles;
            drop table if exists credentials;
            drop table if exists people;
        ');
        parent::_after();
    }

    /**
     * Check if an entity has a HasMany relation
     * @test
     */
    public function checkHasManyRelation()
    {
        $filipe = new HasManyPerson(['name' => 'Filipe', 'email' => 'filipe@example.com']);
        $this->assertTrue($filipe->save());
        $post = new HasManyPost(
            [
                'title' => 'A test post',
                'body' => 'A simple body',
                'created' => date("Y-m-d H:i:s"),
                'author' => $filipe
            ]
        );
        $this->assertTrue($post->save());
        $posts = $filipe->posts;
        $this->assertEquals(1, count($posts));
        $this->assertEquals($post->title, $posts[0]->title);
        $firstPost = $posts[0];
        $this->assertEquals($filipe->name, $firstPost->author->name);

        Entity\Manager::getInstance()->get('Orm\Relation\HasManyPost')
            ->getRelation('_author')->lazyLoad = false;
        $posts = HasManyPost::find()->all();
        $this->assertEquals(1, count($posts));
        $this->assertEquals($filipe->name, $posts[0]->author->name);

        $this->assertTrue($filipe->delete());
        $this->testGuy->dontSeeInDatabase('posts', ['title' => 'A test post']);

    }
}

class HasManyPerson extends Entity
{

    /**
     * @readwrite
     * @column
     * @var integer
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
     * @column
     * @var string
     */
    protected $_email;

    /**
     * @readwrite
     * @hasMany Orm\Relation\HasManyPost, foreignKey=person_id, limit=10, dependent=true
     * @var HasManyPost[]
     */
    protected $_posts;

    /**
     * @readwrite
     * @var string
     */
    protected $_configFile = 'orm-db-settings';

    /**
     * @readwrite
     * @var string
     */
    protected $_configName = 'orm-db';

    /**
     * @read
     * @var string
     */
    protected $_tableName = 'people';
}

class HasManyPost extends Entity
{
    /**
     * @readwrite
     * @column
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_title;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_body;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_created;

    /**
     * @readwrite
     * @belongsTo Orm\Relation\HasManyPerson, foreignKey=person_id, lazyLoad=true
     * @var HasManyPerson
     */
    protected $_author;

    /**
     * @readwrite
     * @var string
     */
    protected $_configFile = 'orm-db-settings';

    /**
     * @readwrite
     * @var string
     */
    protected $_configName = 'orm-db';

    /**
     * @read
     * @var string
     */
    protected $_tableName = 'posts';
}