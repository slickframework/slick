<?php

/**
 * Sqlite change data functional test case
 *
 * @package   Test\Database\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sqlite;

use Slick\Database\Adapter;
use Slick\Database\Adapter\SqliteAdapter;
use Slick\Database\Schema;
use Slick\Database\Sql;

/**
 * Sqlite change data functional test case
 *
 * @package   Test\Database\Sqlite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ChangeDataTest extends \Codeception\TestCase\Test
{
    /**
     * @var SqliteAdapter
     */
    protected $_adapter;

    /**
     * Initialize the Sqlite adapter
     */
    protected function _before()
    {
        parent::_before();

        $dbFile = __DIR__ .'/myTest.db';
        $adapter = new Adapter([
            'driver' => 'Sqlite',
            'options' => [
                'file' => $dbFile
            ]
        ]);
        /** @var Adapter\MysqlAdapter $mysql */
        $this->_adapter = $adapter->initialize();
        /** @var Schema $schema */
        $schema = include(__DIR__ . '/schemaDef.php');

        $schema->setAdapter($this->_adapter);
        $this->_adapter->execute($schema->getCreateStatement());
    }

    /**
     * Cleans up every thing for next test
     */
    protected function _after()
    {
        $dbFile = __DIR__ .'/myTest.db';
        if (is_file($dbFile)) {
            //copy($dbFile, __DIR__ .'/myTest-result.db');
            unlink($dbFile);
        }
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Basic insert remove and change operations
     * @test
     */
    public function addChangeAndRemove()
    {
        $result = Sql::createSql($this->_adapter)->insert('tags')->set([
            'name' => 'PHP'
        ])->execute();
        $this->assertEquals(1, $result);
        Sql::createSql($this->_adapter)->insert('tags')->set([
            'name' => 'Javascript'
        ])->execute();
        $tags = Sql::createSql($this->_adapter)->select('tags')->all();
        $this->assertEquals(2, $tags->count());
        $this->assertEquals('PHP', $tags[0]['name']);

        $result = Sql::createSql($this->_adapter)->update('tags')->set([
            'name' => 'PHP5'
        ])->where(['id = :id' => [':id' => 1]])->execute();
        $this->assertEquals(1, $result);

        $tag = Sql::createSql($this->_adapter)
            ->select('tags')
            ->where(['id = ?' => 1])
            ->first();
        $this->assertEquals('PHP5', $tag['name']);

        $tags = Sql::createSql($this->_adapter)->select('tags')->limit(1,1)->all();
        $this->assertEquals(1, count($tags));
        $this->assertEquals('Javascript', $tags[0]['name']);

        Sql::createSql($this->_adapter)->insert('people')->set([
            'name' => 'Filipe Silva',
            'email' => 'filipe@example.com'
        ])->execute();
        $personId = $this->_adapter->getLastInsertId();
        Sql::createSql($this->_adapter)->insert('posts')->set([
            'title' => 'My post',
            'body' => 'post body',
            'created' => date("Y-m-d H:i:s"),
            'person_id' => $personId
        ])->execute();
        $postId = $this->_adapter->getLastInsertId();
        Sql::createSql($this->_adapter)->insert('posts_tags')->set([
            'post_id' => $postId,
            'tag_id' => $tag['id']
        ])->execute();

        $post = Sql::createSql($this->_adapter)->select('posts', ['title', 'body'])
            ->join('people', 'people.id = posts.person_id', ['name AS author'])
            ->join('posts_tags', 'posts.id = posts_tags.post_id', null)
            ->join('tags', 'tags.id = posts_tags.tag_id', ['name AS tagName'])
            ->order('posts.created DESC')
            ->first();

        $expected = [
            'title' => 'My post',
            'body' => 'post body',
            'author' => 'Filipe Silva',
            'tagName' => 'PHP5'
        ];
        $this->assertEquals($expected, $post);
    }
}
