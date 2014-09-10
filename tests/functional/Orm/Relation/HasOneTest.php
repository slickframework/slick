<?php

/**
 * Orm HasOne relation test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Orm\Relation;

use Orm\Person;
use Slick\Orm\Entity;
use Slick\Database\Schema;
use Slick\Configuration\Configuration;
use Slick\Database\Adapter\MysqlAdapter;

/**
 * Orm HasOne relation test case
 *
 * @package   Test\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class HasOneTest extends \Codeception\TestCase\Test
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
     * Create an HasOne relation
     * @test
     */
    public function createHasOneRelation()
    {
        $filipe = new HasOnePerson(
            [
                'name' => 'filipe',
                'email' => 'filipe@example.com'
            ]
        );
        $this->assertTrue($filipe->save());
        $profile = new HasOneProfile(
            [
                'timeZone' => 'Atlantic/Azores',
                'language' => 'pt_PT',
                'person' => $filipe
            ]
        );
        $relation = Entity\Manager::getInstance()->get($filipe)
            ->getRelation('_profile');

        $this->assertInstanceOf('Slick\Orm\Relation\HasOne', $relation);
        $this->assertTrue($profile->save());

        $filipe = HasOnePerson::get(1);
        $this->assertEquals('pt_PT', $filipe->profile->language);

        $relation->lazyLoad = true;
        $filipe = HasOnePerson::get(1);
        $this->assertEquals('pt_PT', $filipe->profile->language);
    }

}

/**
 * Class HasOnePerson
 * @package Orm\Relation
 */
class HasOnePerson extends Entity
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
     * @hasOne Orm\Relation\HasOneProfile, foreignKey=person_id
     * @var HasOneProfile
     */
    protected $_profile;

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

/**
 * Class HasOneProfile
 * @package Orm\Relation
 */
class HasOneProfile extends Entity
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
    protected $_language;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_timeZone;

    /**
     * @readwrite
     * @column
     * @var string
     */
    protected $_picture;

    /**
     * @readwrite
     * @belongsTo Orm\Relation\HasOnePerson, foreignKey=person_id
     * @var HasOnePerson
     */
    protected $_person;

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
    protected $_tableName = 'profiles';
}