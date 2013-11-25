<?php

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;
use Codeception\Util\Stub,
    Slick\Database\Database;

/**
 * CREATE TABLE statment test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slcik\Database\Query\Ddl\Create
     */
    protected $_create;

    protected $_query;

    /**
     * Set up SUT for tests
     */
    protected function _before()
    {
        $db = new Database(array('type' => 'sqlite'));
        $db = $db->initialize();
        $this->_query = $db->connect()->ddlQuery();
        $this->_create = $this->_query->create('users');
        unset($db);
    }

    protected function _after()
    {
        unset ($this->_query, $this->_create);
    }

    /**
     * Create a new statement
     * @test
     */
    public function retreiveCreateQuery()
    {
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $this->_create);
    }

}