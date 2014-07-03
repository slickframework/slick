<?php

/**
 * Update SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql;

use Slick\Database\Sql;
use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;

/**
 * Update SQL test case
 *
 * @package   Test\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UpdateTest extends \Codeception\TestCase\Test
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
     * Crate and parse an update sql object
     * @test
     */
    public function parseUpdateStatement()
    {
        $sql = Sql::createSql($this->_adapter)->update('users');
        $this->assertInstanceOf('Slick\Database\Sql\Update', $sql);
        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $sql);
        $sql->set(['name' => 'silva'])
            ->where(['id = :id' => [':id' => 4]]);
        $expected = "UPDATE users SET (name = :name) WHERE id = :id";
        $this->assertEquals($expected, $sql->getQueryString());
        $this->assertEquals(
            [
                ':name' => 'silva',
                ':id' => 4
            ],
            $sql->getParameters()
        );
    }
}