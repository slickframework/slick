<?php

/**
 * Drop test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Sql\Dialect;

use Codeception\Util\Stub;
use Slick\Database\Database;

/**
 * Drop test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTest extends \Codeception\TestCase\Test
{
    
    /**
     * @var \Slick\Database\Query\Ddl\Alter
     */
    protected $_drop;

    /**
     * @var string Stores the requested query
     */
    protected static $_lastQuery;

    /**
     * @var array Stores the params on excute command
     */
    protected static $_usedParams = array();

    /**
     * Set the SUT qlter statement
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(array('type' => 'sqlite'));
        $db = $db->initialize();
        $this->_drop = $db->connect()->ddlQuery()->drop('users');
    }

    /**
     * A mocked query object
     */
    protected function _mockQuery()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'Mysql',
                    'connector' => $this->_drop->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    self::$_usedParams = $params;
                    return true;
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_drop->setQuery($query);
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_drop);
        parent::_after();
    }

    /**
     * Drop a table
     * @test
     */
    public function dropATable()
    {
        $this->_mockQuery();
        $this->_drop->execute();
        $expected = "DROP TABLE `users`";
        $this->assertEquals($expected, self::$_lastQuery);
    }
}