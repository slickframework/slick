<?php

/**
 * Delete SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql;

/**
 * Delete SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DeleteTest extends \Codeception\TestCase\Test
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
     * Trying to parse delete statement
     * @test
     */
    public function parseDeleteStatement()
    {
        $sql = Sql::createSql($this->_adapter)->delete('users');
        $sql->where(['users.id = ?' => 2]);
        $expected = "DELETE FROM users WHERE users.id = ?";
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals([2], $sql->getParameters());
    }
}