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
        $expected = "SELECT * FROM users";
        $this->assertEquals($expected, $sql->getQueryString());
    }
}
