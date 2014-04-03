<?php

/**
 * Select statment test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Sql;

use Codeception\Util\Stub,
    Slick\Database\Database;

/**
 * Select statement test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectTest extends \Codeception\TestCase\Test
{
    /**
     * @var \Slick\Database\Connector\Mysql
     */
    protected $_connector = null;

    /**
     * @var \Slick\Database\Query\Sql\Select
     */
    protected $_select = null;

    protected static $_lastQuery;
    protected static $_usedParams = array();
   
    /**
     * Creates the databse connector
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(
            array(
                'type' => 'mysql',
                'options' => array(
                    'username' => 'test',
                    'password' => '',
                    'hostname' => 'localhost',
                    'database' => 'test'
                )
            )
        );
        $this->_connector = $db->initialize();
        $this->_select = $this->_connector->query()->select('users');
        unset($db);
    }

    /**
     * Unsets connector for next test
     */
    protected function _after()
    {
        $this->_connector = null;
        $this->_select = null;
        parent::_after();
    }

    /**
     * Check select object class structure
     * @test
     */
    public function checkSelectClassStructure()
    {
        $this->assertInstanceOf('\Slick\Database\Query\Sql\SqlInterface', $this->_select);
        $this->assertInstanceOf('\Slick\Database\Query\Sql\AbstractSql', $this->_select);
        $this->assertInstanceOf('\Slick\Database\Query\Sql\SelectInterface', $this->_select);
        $this->assertInstanceOf('\Slick\Database\Query\Sql\Select', $this->_select);

        $this->assertEquals('users', $this->_select->tableName);
        $this->assertEquals(array('*'), $this->_select->fields);
        $this->assertEmpty($this->_select->params);
        $this->assertInstanceOf('Slick\Database\Query\Sql\Conditions', $this->_select->conditions);
        $this->assertInstanceOf('Slick\Database\Query\Query', $this->_select->query);
    }

    /**
     * Add where conditions
     * @test
     */
    public function addWhereConditions()
    {
        $expectedPredicates = array('id = ?');
        $expectedParams = array(2);
        $expectedOperations = array();

        $this->_select->where(array('id = ?' => 2));
        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);

        $expectedPredicates[] = 'name LIKE ?';
        $expectedParams[] = '%Filipe%';
        $expectedOperations[] = 'AND';

        $this->_select->andWhere(array('name LIKE ?' => '%Filipe%'));
        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);
    }

    /**
     * add multimple conditions in a single where call
     * @test
     */
    public function multipleConditions()
    {
        $expectedPredicates = array('id = ?', 'name LIKE ?');
        $expectedParams = array(2, '%Filipe%');
        $expectedOperations = array('AND');

        $this->_select->where(array('id = ?' => 2, 'name LIKE ?' => '%Filipe%'));

        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);
    }

    /**
     * Using named params on where clauses
     * 
     * @test
     */
    public function namedParams()
    {
        $expectedPredicates = array('id = :id', 'name LIKE :name', 'test != ?');
        $expectedParams = array(':id' => 2, ':name' => '%Filipe%', '0');
        $expectedOperations = array('AND', 'AND');

        $this->_select->where(
            array(
                'id = :id' => array(':id' => 2),
                'name LIKE :name' => array(':name' => '%Filipe%'),
                'test != ?' => array('0')
            )
        );

        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);
    }

    /**
     * Detest IN predicate and use the array as list
     * @test
     */
    public function addingListsToWhere()
    {
        $expectedPredicates = array('id IN (?)');
        $expectedParams = array('1, 2, 3, 4');
        $expectedOperations = array();

        $this->_select->where(
            array(
                'id IN (?)' => array(1, 2, 3, 4)
            )
        );

        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);
    }

    /**
     * Add where OR operantion to conditions
     * @test
     */
    public function addWhereOrConditions()
    {
        $expectedPredicates = array('id = ?');
        $expectedParams = array(2);
        $expectedOperations = array();

        $this->_select->where(array('id = ?' => 2));
        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);

        $expectedPredicates[] = 'name LIKE ?';
        $expectedParams[] = '%Filipe%';
        $expectedOperations[] = 'OR';

        $this->_select->orWhere(array('name LIKE ?' => '%Filipe%'));
        $this->assertEquals($expectedParams, $this->_select->params);
        $this->assertEquals($expectedPredicates, $this->_select->conditions->predicates);
        $this->assertEquals($expectedOperations, $this->_select->conditions->operations);
    }

    /**
     * Settin a select query an check all return
     * @test
     */
    public function gelAllQuery()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'Mysql',
                    'connector' => $this->_select->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    return true;
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_select->setQuery($query);
        $this->_select
            ->where(array("id = '?'" => 23))
            ->andWhere(array("Active = :active" => array(':active' => true)))
            ->orderBy('name DESC')
            ->limit(15)
            ->groupBy('Origin')
            ->all();
        $expected = <<<EOS
SELECT * FROM users
WHERE id = '?' AND Active = :active
GROUP BY Origin
ORDER BY name DESC
LIMIT 15
EOS;
        $this->assertEquals(trim($expected), self::$_lastQuery);

        $this->_select
            ->limit(10, 1)
            ->join('profile', 'profile.user_id = users.id', array('picture', 'path'))
            ->all();
        $expected = <<<EOS
SELECT users.*, profile.picture, profile.path FROM users
LEFT JOIN profile ON profile.user_id = users.id
WHERE id = '?' AND Active = :active
GROUP BY Origin
ORDER BY name DESC
LIMIT 1, 10
EOS;
        $this->assertEquals(trim($expected), self::$_lastQuery);
    }

    /**
     * Check transformer dialect creation
     * @test
     * @expectedException Slick\Database\Exception\UndefinedSqlDialectException
     * @expectedExceptionMessage The dialect 'OtherSql' is not defined.
     */
    public function checkTransformerDialectCreation()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'OtherSql',
                    'connector' => $this->_select->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    return true;
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_select->setQuery($query);
        $this->_select->all();
    }

    /**
     * Check first method on select 
     * @test
     */
    public function checkFirstMethod()
    {

        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'Mysql',
                    'connector' => $this->_select->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    self::$_usedParams = $params;
                    return new \ArrayObject(
                        array(
                            0 => array(
                                'id' => 1,
                                'name' => 'Filipe Silva',
                                'email' => 'silvam.filipe@gmail'
                            )
                        )
                    );
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_select->setQuery($query);
        $this->_select
            ->where(array('id = ?' => 1))
            ->first();

        $expected = <<<EOS
SELECT * FROM users
WHERE id = ?
LIMIT 1
EOS;
        $this->assertEquals(array(1), self::$_usedParams);
        $this->assertEquals(trim($expected), self::$_lastQuery);

    }

/**
     * Check count method on select 
     * @test
     */
    public function checkCountMethod()
    {

        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'Mysql',
                    'connector' => $this->_select->query->connector
                )
            ),
            array(
                'execute' => function($params) {
                    self::$_usedParams = $params;
                    return new \ArrayObject(
                        array(
                            0 => array(
                                'totalRows' => 123,
                            )
                        )
                    );
                },
                'prepare' => function($sql) {
                    self::$_lastQuery = $sql;
                    return $sql;
                }
            )
        );
        $this->_select->setQuery($query);
        $result = $this->_select
            ->where(array('id = ?' => 2))
            ->join('profile', 'profile.user_id = users.id', array('picture', 'path'))
            ->count();

        $expected = <<<EOS
SELECT COUNT(*) AS totalRows FROM users
LEFT JOIN profile ON profile.user_id = users.id
WHERE id = ?
EOS;
        $this->assertEquals(array(2), self::$_usedParams);
        $this->assertEquals(trim($expected), self::$_lastQuery);
        $this->assertEquals(123, $result);
    }
}