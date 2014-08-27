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
        $filipe = new Person(
            [
                'name' => 'Filipe',
                'email' => 'filipe@example.com'
            ]
        );
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