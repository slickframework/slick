<?php

/**
 * Table definition test case
 *
 * @package   Test\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Definition;

use Codeception\Util\Stub;
use Slick\Database\Database,
    Slick\Database\RecordList,
    Slick\Database\Connector\Mysql,
    Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Database\Definition\TableDefinition;

/**
 * Table definition test case
 *
 * @package   Test\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TableDefinitionTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slick\Database\Definition\TableDefinition
     */
    protected $_tableDefinition;

    protected static $_lastQuery;

    /**
     * Create te SUT object
     */
    protected function _before()
    {
        parent::_before();

        $query = Stub::construct(
            'Slick\Database\Query\DDLQuery',
            array(
                array(
                    'dialect' => 'Mysql',
                )
            ),
            array(
                'execute' => function($params) {                    
                    return new RecordList();
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $db = MockConnector::getInstance($query);
        
        $this->_tableDefinition = new TableDefinition('users', $db);
        $this->_tableDefinition->load();
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_tableDefinition);
        parent::_after();
    }

    /**
     * Construction
     * @test
     */
    public function createNewTableDefinition()
    {
        $this->assertEquals('Mysql', $this->_tableDefinition->dialect);
        $result = $this->_tableDefinition->getResultSet();
        $this->assertInstanceOf('Slick\Database\RecordList', $result);
    }

    /**
     * Get the parser for the dialect
     * @test
     */
    public function getParser()
    {
        $parser = $this->_tableDefinition->getParser();
        $this->assertInstanceOf(
            'Slick\Database\Definition\Parser\ParserInterface',
            $parser
        );
    }

    /**
     * Retrieving columns from definition
     * @test
     */
    public function getColumns()
    {
        $parser = Stub::make(
            'Slick\Database\Definition\Parser\Mysql',
            array(
                'getColumns' => function() {
                    return new ElementList();
                }
            )
        );
        $this->_tableDefinition->parser = $parser;
        $columns = $this->_tableDefinition->getColumns();
        $this->assertInstanceOf(
            'Slick\Database\Query\Ddl\Utility\ElementList',
            $columns
        );
    }

    /**
     * Retrieve indexes from definition
     * @test
     */
    public function getIndexes()
    {
        $parser = Stub::make(
            'Slick\Database\Definition\Parser\Mysql',
            array(
                'getIndexes' => function() {
                    return new ElementList();
                }
            )
        );
        $this->_tableDefinition->parser = $parser;
        $indexes = $this->_tableDefinition->getIndexes();
        $this->assertInstanceOf(
            'Slick\Database\Query\Ddl\Utility\ElementList',
            $indexes
        );
    }

}

class MockConnector extends Mysql
{
    /**
     * @readwrite
     * @var [type]
     */
    protected $_query;

    protected function __construct($query)
    {
        parent::__construct(array('query' => $query));
    }

    public static function getInstance($options = array())
    {
        static $instance;
        if (
            !is_a(
                $instance,
                'Slick\Database\Connector\ConnectorInterface'
            )
        ) {
            $instance = new MockConnector($options);
        }
        return $instance;
    }

    public function connect()
    {
        $this->dataObject = new StdClass();
        $this->_connected = true;
        return $this;
    }

    public function ddlQuery($sql = null)
    {
        return $this->_query;
    }
}