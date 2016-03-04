<?php

/**
 * Drop table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl;

use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\DropTable;

/**
 * Drop table DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTableTest extends \Codeception\TestCase\Test
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
     * Trying to create a drop table query
     * @test
     */
    public function dropTableQuery()
    {
        $ddl = new DropTable('users');

        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $ddl);

        $expected = "DROP TABLE users";
        $this->assertEquals($expected, $ddl->setAdapter($this->_adapter)->getQueryString());
    }
}
