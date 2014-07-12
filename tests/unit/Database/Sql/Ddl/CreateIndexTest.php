<?php

/**
 * Create index DDL test case
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
use Slick\Database\Sql\Ddl\CreateIndex;

/**
 * Create index DDL test case
 *
 * @package   Test\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateIndexTest extends \Codeception\TestCase\Test
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
     * Create a create index object
     * @test
     */
    public function createCreateIndexObject()
    {
        $ddl = new CreateIndex('dateIndex', 'users');
        $ddl->setAdapter($this->_adapter);

        $names = 'startDate, endDate';
        $obj = $ddl->setColumns($names);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\CreateIndex', $obj);
        $this->assertInstanceOf('Slick\Database\Sql\SqlInterface', $ddl);
        $expected = ['startDate', 'endDate'];
        $this->assertEquals($expected, $ddl->getColumnNames());

        $expected = 'CREATE INDEX dateIndex ON users (startDate, endDate)';
        $this->assertEquals($expected, $ddl->getQueryString());
    }
}
