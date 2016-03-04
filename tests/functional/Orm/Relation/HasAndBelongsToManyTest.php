<?php

/**
 * Orm HasAndBelongsToMany relation test case
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
 * Class HasAndBelongsToManyTest
 * @package Orm\Relation
 */
class HasAndBelongsToManyTest extends \Codeception\TestCase\Test
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
     * Creates a relation
     * @test
     */
    public function createHasAndBelongsToManyRelation()
    {
        $php = new HabtmTag(['name' => 'PHP']);
        $this->assertTrue($php->save());
        $javaScript = new HabtmTag(['name' => 'JavaScript']);
        $this->assertTrue($javaScript->save());
        $post = new HabtmPost(
            [
                'title' => 'Just a post',
                'body' => 'Some body content',
                'created' => date("Y-m-d H:i:s"),
                'tags' => [$php, $javaScript]
            ]
        );
        $this->assertTrue($post->save());
        $post = HabtmPost::get(1);
        $this->assertInstanceOf('Slick\Database\RecordList', $post->tags);
        $this->assertEquals($php->name, $post->tags[0]->name);

        $post->tags = null;
        $this->assertTrue($javaScript->delete());
        $this->assertEquals(1, count($post->tags));
    }

}

class HabtmPost extends Entity
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
     * @hasAndBelongsToMany Orm\Relation\HabtmTag
     * @var HabtmTag[]
     */
    protected $_tags;

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

class HabtmTag extends Entity
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
    protected $_tableName = 'tags';

    /**
     * @readwrite
     * @hasAndBelongsToMany Orm\Relation\HabtmPost
     * @var HabtmPost[]
     */
    protected $_posts;
}
