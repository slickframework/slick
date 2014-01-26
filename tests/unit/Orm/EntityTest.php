<?php

/**
 * Entity test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Orm;

use Codeception\TestCase\Test;
use Codeception\Util\Stub;
use Slick\Database\Connector\SQLite;
use Slick\Orm\Entity;

/**
 * Entity test case
 *
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntityTest extends Test
{

    /**
     * @var User
     */
    protected $_user;

    /**
     * Prepare entity
     */
    protected function _before()
    {
        parent::_before();
        $connector = SQLite::getInstance();
        $connector->file = ":memory:";

        $this->_user = new User(['connector' => $connector]);
    }

    /**
     * clean up after each test
     */
    protected function _after()
    {
        unset($this->_user);
        parent::_after();
    }

    /**
     * Crate an entity and check default values
     * @test
     */
    public function crateEntity()
    {
        $this->assertEquals('User', $this->_user->getAlias());
        $this->assertEquals('users', $this->_user->getTable());
        $this->assertEquals('id', $this->_user->primaryKey);
        $this->assertInstanceOf('Slick\Database\Connector\SQLite', $this->_user->connector());
        $this->assertInstanceOf('Slick\Database\Query\QueryInterface', $this->_user->query());
    }

    /**
     * Gets the column definition for this entity
     * @test
     */
    public function getColumnDefinition()
    {
        $columns = $this->_user->getColumns();
        $this->assertTrue($columns->hasColumn('id'));
        $this->assertTrue($columns->hasColumn('name'));
        $this->assertTrue($columns['id']->primaryKey);
        $this->assertEquals('int', $columns['id']->type);
        $this->assertEquals('big', $columns['id']->size);
        $this->assertTrue($columns['id']->unsigned);
        $this->assertEquals('_id', $columns['id']->raw);
        $this->assertFalse($columns['id']->index);

        $this->assertEquals('required' ,$columns['name']->validate->value);
        $this->assertEquals('text' ,$columns['name']->type);


    }

    /**
     * Retrieves the entity by providing the primary key id
     * @test
     */
    public function getEntityById()
    {

    }

}

/**
 * Class User
 * @package Orm
 */
class User extends Entity
{

    /**
     * @column type=int, size=big, unsigned, primary
     * @readwrite
     * @var integer
     */
    protected $_id;

    /**
     * @readwrite
     * @column type=text, length=255
     * @validate required
     * @var string
     */
    protected $_name;


}