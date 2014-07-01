<?php

/**
 * Select SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Adapter;
use Slick\Database\Sql;

/**
 * Select SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectTest extends \Codeception\TestCase\Test
{

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * Prepares for test
     */
    protected function _before()
    {
        parent::_before();
        $this->_adapter = new Adapter(['options' => ['autoConnect' => false]]);
        $this->_adapter = $this->_adapter->initialize();
    }

    /**
     * Cleans for next test
     */
    protected function _after()
    {
        unset($this->_adapter);
        parent::_after();
    }

    /**
     * Trying to create a basis SQL select statement
     * @test
     */
    public function createBasicSelectStatement()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $this->assertInstanceOf('Slick\Database\Sql\Select', $sql);
        $expected = "SELECT users.* FROM users";
        $this->assertEquals($expected.' FETCH FIRST 100 ROWS ONLY', $sql->getQueryString());
        $sql->where(['id = :id' => [':id' => 1]]);
        $expected .= " WHERE id = :id";
        $this->assertEquals($expected.' FETCH FIRST 100 ROWS ONLY', $sql->getQueryString());
        $sql->setDistinct(true);
        $expected = "SELECT DISTINCT users.* FROM users";
        $expected .= " WHERE id = :id FETCH FIRST 100 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());
    }

    /**
     * Trying to do a select with joins
     * @test
     */
    public function doSelectWithJoins()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', null);
        $expected = "SELECT users.* FROM users LEFT JOIN roles ON roles.id = users.role_id FETCH FIRST 100 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', ['*']);
        $expected = "SELECT users.*, roles.* FROM users LEFT JOIN roles ON roles.id = users.role_id FETCH FIRST 100 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());

        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->join('roles', 'roles.id = users.role_id', ['*'], 'Role', Sql\Select\Join::JOIN_INNER);
        $expected = "SELECT users.*, Role.* FROM users INNER JOIN roles AS Role ON roles.id = users.role_id FETCH FIRST 100 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());
    }

    /**
     * Do a select with order by
     * @test
     */
    public function selectWithOrderBy()
    {
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->order('users.age DESC');
        $expected = "SELECT users.* FROM users ORDER BY users.age DESC FETCH FIRST 100 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());

    }

    /**
     * Tests simple limit
     * @test
     */
    public function selectSimpleLimit()
    {
        //FETCH FIRST n ROWS ONLY
        $sql = Sql::createSql($this->_adapter)->select('users');
        $sql->limit(10);
        $expected = "SELECT users.* FROM users FETCH FIRST 10 ROWS ONLY";
        $this->assertEquals($expected, $sql->getQueryString());
    }
}
