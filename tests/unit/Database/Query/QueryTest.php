<?php

/**
 * Query test case
 *
 * @package   Test\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query;

use Codeception\Util\Stub;
use Slick\Database\Database;

/**
 * Query test case
 *
 * @package   Test\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class QueryTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slick\Database\Query\Query
     */
    public $_query = null;

    /**
     * Prepares SUT for test
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(
            array(
                'type' => 'sqlite',
                'options' => array(
                    'file' => ':memory:'
                )
            )
        );
        $db = $db->initialize();
        $this->_query = $db->connect()->query();
    }

    /**
     * Clean upt for next test
     */
    protected function _after()
    {
        $this->_query = null;
        parent::_after();
    }

    /**
     * prepare and execute
     * @test
     * @expectedException Slick\Database\Exception\InvalidSqlException
     */
    public function prepareAndExecuteQuery()
    {
        $pst = $this->_query->prepare("CREATE TABLE t(x INTEGER, y, z, PRIMARY KEY(x ASC))");
        $this->_query->execute();
        $this->_query->prepare("Some Silly Query");
    }

    /**
     * Call prepare without connect
     * @test
     * @expectedException Slick\Database\Exception\ServiceException
     */
    public function callPrepareWithoutConnect()
    {
        $this->_query->connector->disconnect();
        $this->_query->prepare("test");
    }

    /**
     * Check execute sql exception
     * @test
     * @expectedException Slick\Database\Exception\InvalidSqlException
     */
    public function callValidSqlWithException()
    {
        $pst = $this->_query->prepare("CREATE TABLE t(x INTEGER, y, z, PRIMARY KEY(x ASC))");
        $this->_query->execute();
        $stsm = $this->_query->prepare("ALTER TABLE `t` RENAME TO test");
        $this->_query->execute(array('name' => 'users'));
    }

}