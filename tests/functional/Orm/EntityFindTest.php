<?php

/**
 * Orm Entity test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm;

use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Slick\Orm\Entity;
use Slick\Database\Sql;
use Slick\Database\Schema;
use Slick\Configuration\Configuration;
use Slick\Database\Adapter\MysqlAdapter;
use Slick\Orm\Events\Select;
use Zend\EventManager\SharedEventManager;

/**
 * Orm Entity test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntityFindTest extends \Codeception\TestCase\Test
{

    /**
     * @var MysqlAdapter
     */
    protected $_adapter;

    /**
     * Runs before each test
     */
    protected function _before()
    {
        parent::_before();
        Configuration::addPath(__DIR__);
        $cfg = include('orm-db-settings.php');
        $this->_adapter = new MysqlAdapter($cfg['orm-db']['options']);
        /** @var Schema $schemaDef */
        $schemaDef = include('schema_def.php');
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
     * Checks the find all and find first methods
     * @test
     */
    public function findAllAndFindFirst()
    {
        $container = ContainerBuilder::buildContainer([
            'sharedEventManager' => Definition::object(
                    'Zend\EventManager\SharedEventManager'
                )
        ]);
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        Sql::createSql($this->_adapter)->insert('tags')->set([
            'name' => 'PHP'
        ])->execute();
        Sql::createSql($this->_adapter)->insert('tags')->set([
            'name' => 'Javascript'
        ])->execute();

        /** @var SharedEventManager $sharedEvents */
        $sharedEvents = $container->get('sharedEventManager');

        $sharedEvents->attach('Orm\Tag', Select::BEFORE_SELECT, function (Select $event) {
            if ($event->action == Select::FIND_ALL) {
                $this->assertInstanceOf('Slick\Orm\Sql\Select', $event->sqlQuery);
                $this->assertFalse($event->singleItem);
            } else {
                $this->assertInstanceOf('Slick\Orm\Sql\Select', $event->sqlQuery);
                $this->assertTrue($event->singleItem);
            }
        });

        $sharedEvents->attach('Orm\Tag', Select::AFTER_SELECT, function (Select $event) {
            if ($event->action == Select::FIND_ALL) {
                $this->assertEquals(2, count($event->data));
            } else {
                $this->assertEquals('Javascript', $event->data['name']);
            }
        });

        $sharedEvents->attach('Orm\Tag', Select::BEFORE_COUNT, function(Select $event) {
            $this->assertInstanceOf('Slick\Orm\Sql\Select', $event->sqlQuery);
            $this->assertTrue($event->singleItem);
        });

        $container->set('sharedEventManager', $sharedEvents);

        $tags = Tag::find()->all();
        $this->assertInstanceOf('Slick\Database\RecordList', $tags);
        $this->assertEquals(2, count($tags));
        $this->assertInstanceOf('Orm\Tag', $tags[0]);

        $php = Tag::find()->first();
        $this->assertInstanceOf('Orm\Tag', $php);
        $this->assertEquals('Javascript', $php->name);
        $this->assertEquals(2, Tag::find()->count());
    }

}

/**
 * Tag entity
 *
 * @package Test\Orm
 */
class Tag extends Entity
{
    /**
     * @readwrite
     * @var int
     */
    protected $_id;

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var string
     */
    protected $_created;

    /**
     * @readwrite
     * @var string
     */
    protected $_updated;

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
}
