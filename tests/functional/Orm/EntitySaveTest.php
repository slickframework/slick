<?php

/**
 * Orm Entity save test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm;

use Slick\Configuration\Configuration;
use Slick\Database\Adapter\MysqlAdapter;
use Slick\Database\Schema;
use Slick\Orm\Entity;
use Slick\Orm\Events\Delete;
use Slick\Orm\Events\Save;

/**
 * Orm Entity save test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntitySaveTest extends \Codeception\TestCase\Test
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
     * Save (insert/update) records
     * @test
     */
    public function saveRecords()
    {
        $data = [
            'name' => 'Filipe',
            'email' => 'filipe@example.com'
        ];
        $filipe = new Person($data);

        $filipe->getEventManager()->attach(Save::BEFORE_SAVE, function(Save $event) use ($data) {
            if ($event->action == Save::INSERT) {
                $this->assertEquals($data, $event->data);
            } else {
                $this->assertEquals([
                    'name' => 'Filipe Silva',
                    'email' => 'filipe@example.com'
                ], $event->data);
            }
        });
        $filipe->getEventManager()->attach(Save::AFTER_SAVE, function(Save $event) use ($data) {
            if ($event->action == Save::INSERT) {
                $this->assertEquals($data, $event->data);
                $this->assertEquals(1, $event->getTarget()->id);
            } else {
                $this->assertEquals(1, $event->getTarget()->id);
            }
        });
        $this->assertTrue($filipe->save());
        $this->assertEquals(1, $filipe->id);
        $filipe->name = 'Filipe Silva';
        $this->assertTrue($filipe->save());

        $this->testGuy->seeInDatabase(
            'people',
            ['id' => 1, 'name' => 'Filipe Silva', 'email' => 'filipe@example.com']
        );

        $jon = new Person();
        $this->assertTrue($jon->save(['name' => 'Jon', 'email' => 'jon@example.com']));
        $this->assertEquals(2, $jon->id);

        $this->testGuy->seeInDatabase(
            'people',
            [
                'id' => 2,
                'name' => 'jon',
                'email' => 'jon@example.com'
            ]
        );

        $this->assertTrue($jon->save(['name' => 'Jon Doe']));
        $this->testGuy->seeInDatabase('people', ['name' => 'Jon Doe']);

        $fake = new Person(['name' => 'fake']);
        $fake->getEventManager()->attach(Save::BEFORE_SAVE, function(Save $event) {
            if (!isset($event->data['email']) || !$event->data['email']) {
                $event->abort = true;
            }
        });
        $this->assertFalse($fake->save());
        $this->assertTrue($fake->setEmail('fake@example.com')->save());
        $fake->email = null;
        $this->assertFalse($fake->save());

        $this->assertTrue($jon->delete());
        $this->testGuy->dontSeeInDatabase('people', ['name' => 'Jon Doe']);

        $fake->getEventManager()->attach(Delete::BEFORE_DELETE, function(Delete $event) {
            $event->abort = true;
        });
        $this->assertFalse($fake->delete());

    }

}


class Person extends Entity
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
     * @var string
     */
    protected $_configFile = 'orm-db-settings';

    /**
     * @readwrite
     * @var string
     */
    protected $_configName = 'orm-db';
}