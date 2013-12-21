<?php
/**
 * Definition test case
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
 * Definition test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Query\Ddl\Alter
     */
    protected $_definition;

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
        $this->_definition = $db->connect()->ddlQuery()->definition('users');
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
                    'dialect' => 'SQLite',
                    'connector' => $this->_definition->query->connector
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
        $this->_definition->setQuery($query);
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_definition);
        parent::_after();
    }

    /**
     * Drop a table
     * @test
     */
    public function dropATable()
    {
        $this->_mockQuery();
        $this->_definition->execute();
        $expected = "SELECT * FROM sqlite_master WHERE tbl_name='users'";
        $this->assertEquals($expected, self::$_lastQuery);
    }

}