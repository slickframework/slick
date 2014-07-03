<?php

/**
 * Insert SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Adapter;
use Slick\Database\Sql;
use Slick\Database\Adapter\AdapterInterface;

/**
 * Class InsertTest
 * @package Database\Sql
 */
class InsertTest extends \Codeception\TestCase\Test
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
     * Crate and parse an insert sql object
     * @test
     */
    public function parseInsertStatement()
    {
        $sql = Sql::createSql($this->_adapter)->insert('users');
        $sql->set(
            [
                'name' => 'filipe',
                'email' => 'filipe@example.com'
            ]
        );
        $expected = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals(
            [
                ':name' => 'filipe',
                ':email' => 'filipe@example.com'
            ],
            $sql->getParameters()
        );
    }
}
