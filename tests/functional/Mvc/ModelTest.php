<?php

/**
 * MVC Model test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Slick\Mvc\Model;
use Slick\Database\Schema;
use Codeception\TestCase\Test;
use Slick\Configuration\Configuration;
use Slick\Database\Adapter\MysqlAdapter;

/**
 * MVC Model test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ModelTest extends Test
{
   /**
    * @var \TestGuy
    */
    protected $testGuy;

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
     * Create a model
     * @test
     */
    public function createAModel()
    {
        $php = new MvcModelTag(['name' => 'PHP']);
        $this->assertTrue($php->save());
        $this->assertEquals('name', $php->getDisplayField());
        $expected = [1 => 'PHP'];
        $this->assertEquals($expected, MvcModelTag::getList());
        $this->assertEquals('PHP', (string) $php);
        $this->assertEquals(1, $php->getKey());

        $filipe = new MvcModelPerson();
        $this->assertEquals('email', $filipe->getDisplayField());
    }

}

/**
 * Class MvcModelTag
 * @package Mvc
 */
class MvcModelTag extends Model
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

    /**
     * @readwrite
     * @var string
     */
    protected $_tableName = 'tags';
}

/**
 * Class MvcModelPerson
 * @package Mvc
 */
class MvcModelPerson extends Model
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
     * @display
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

    /**
     * @readwrite
     * @var string
     */
    protected $_tableName = 'people';
}
