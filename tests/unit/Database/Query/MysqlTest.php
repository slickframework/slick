<?php

/**
 * Mysql query test case
 * 
 * @package   Test\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query;

use Codeception\Util\Stub,
    Slick\Database\Query\Mysql as Query;

/**
 * Mysql query test case
 *
 * @package   Test\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    /**
     * The SUT (Mysql query)
     * @var \Slick\Database\Query\Mysql
     */
    protected $query;

    public static $sql = null;

    public static $fail = false;

    public static $mode = 'first';

    /**
     * Creating the environment for tests
     */
    protected function _before()
    {
        parent::_before();
        $this->query = new Query(array(
            'connector' => Stub::make(
                '\Slick\Database\Connector\Mysql',
                array(
                    'escape' => function($val) {
                        return $val;
                    },
                    'getLastError' => function() {
                        return 'Invalid sql mockup';
                    },
                    'getLastInsertId' => function() {return 30;},
                    'getAffectedRows' => function() {return 2;},
                    'execute' => function($sql) {
                        if (MysqlTest::$fail) {
                            MysqlTest::$fail = false;
                            return false;
                        }
                        MysqlTest::$sql = $sql;
                        return Stub::make('\mysqli_result', array(
                            'fetch_fields' => function() {
                                switch (MysqlTest::$mode) {
                                    case 'count':
                                        return  array(
                                            (object) array(
                                                'name' => 'rows',
                                                'table' => null
                                            )
                                        );
                                    case 'first':
                                    default:
                                        return array(
                                            (Object) array(
                                                'name' => 'id',
                                                'table' => 'User'
                                            ),
                                            (Object) array(
                                                'name' => 'name',
                                                'table' => 'User'
                                            ),
                                            (Object) array(
                                                'name' => 'group',
                                                'table' => 'User'
                                            ),
                                        );

                                }
                            },
                            'fetch_array' => function($mode) {
                                static $key;
                                if ($key == 1) {
                                    $key = 0;
                                    return null;
                                }
                                switch (MysqlTest::$mode) {
                                    case 'count':
                                        $key = 1;
                                        return array(3);
                                    case 'first':
                                    default:
                                        $key = 1;
                                        return array(1, 'fsilva', 'admin');
                                }
                            },

                        ));
                    },
                )
            )
        ));
    }

    /**
     * Cleanup for next test.
     */
    protected function _after()
    {
        unset($this->query);
        parent::_after();
    }

    /**
     * Testing the SQL from assign
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function queryFrom()
    {
        $q = $this->query->from('users');
        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);
        $this->assertEquals('users', $this->query->getFrom());
        $fields = $this->query->getFields();
        $this->assertEquals(array('*'), $fields['users']);

        $q = $this->query->from('users', array('id', 'username'));
        $fields = $this->query->getFields();
        $this->assertEquals(array('id', 'username'), $fields['users']);

        $this->query->from(null);
    }

    /**
     * Testing the SQL join assign
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid argument. Enter a valid 'join' value
     */
    public function queryJoin()
    {
        $q = $this->query->join('profiles', 'profile.id = user.id');
        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);

        $this->assertEquals(array('profiles' => array()), $this->query->getFields());
        $this->assertContains('JOIN profiles ON profile.id = user.id', $this->query->getJoin());

        $this->query->leftJoin('profiles', 'profile.id = user.id');
        $this->assertContains('LEFT JOIN profiles ON profile.id = user.id', $this->query->getJoin());


        try {
            $this->query->join('profiles', null);
            $this->fail("This should raise an exception here.");
        } catch(\Slick\Database\Exception\ExceptionInterface $e) {
            $this->assertInstanceOf('\Slick\Database\Exception\InvalidArgumentException', $e);
        }

        $this->query->join(null, null);
    }

    /**
     * Testing the SQL limit assign
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid argument. Enter a valid 'limit' value
     */
    public function queryLimit()
    {
        $q = $this->query->limit(10, 3);
        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);
        $this->assertEquals(10, $this->query->getLimit());
        $this->assertEquals((10 * (3 - 1)), $this->query->getOffset());

        $this->query->limit(null);
    }

    /**
     * Testing the SQL limit assign
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid argument. Enter a valid 'order' value
     */
    public function queryOrderBy()
    {
        $q = $this->query->order('name', 'DESC');

        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);
        $this->assertEquals('name', $this->query->getOrder());
        $this->assertEquals('DESC', $this->query->getDirection());

        $this->query->order(null);
    }

    /**
     * Testing the SQL where clause
     * @test
     * @expectedException \Slick\Database\Exception\InvalidArgumentException
     */
    public function queryWhere()
    {
        $q = $this->query->where("user.id = ?", 1);

        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('Slick\Database\Query', $q);

        $expected = array('clause' => 'user.id = 1', 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $q = $this->query->where("user.name = ?", '%fsilva%');
        $expected = array('clause' => "user.name = '%fsilva%'", 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $q = $this->query->where("user.active = ?", true);
        $expected = array('clause' => "user.active = 1", 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $q = $this->query->where("user.password <> ?", null);
        $expected = array('clause' => "user.password <> NULL", 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());


        $q = $this->query->where("user.age IN (?)", array(2, 4, 5, 6, 7, 8, 10));
        $expected = array('clause' => "user.age IN (2, 4, 5, 6, 7, 8, 10)", 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $q = $this->query->where("user.age LIKE ? OR user.test LIKE ?", array(2, 4));
        $expected = array('clause' => "user.age LIKE 2 OR user.test LIKE 4", 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $this->query->where();
    }

    /**
     * Testing the orWhere andWhere methods
     * @test
     */
    public function queryWhereAlias()
    {
        $q = $this->query->andWhere("user.id = ?", 1);

        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);

        $expected = array('clause' => 'user.id = 1', 'op' => ' AND ');
        $this->assertContains($expected, $this->query->getWhere());

        $q = $this->query->orWhere("user.id = ?", 1);

        $this->assertInstanceOf('\Slick\Database\Query\Mysql', $q);
        $this->assertInstanceOf('\Slick\Database\Query', $q);

        $expected = array('clause' => 'user.id = 1', 'op' => ' OR ');
        $this->assertContains($expected, $this->query->getWhere());
    }

    /**
     * Check the first and all methods
     * @test
     * @expectedException \Slick\Database\Exception\InvalidSqlException
     */
    public function checkFirstAll()
    {
        $this->query
            ->from('users AS User', array('User.id' => 'number', 'User.name'))
            ->where('name LIKE ?', '%fsilva%')
            ->orWhere('name LIKE ?', '%other name%')
            ->order('name', 'ASC')
            ->join('profiles AS Profile', 'User.profile_id = Profile.id', array('Profile.group'))
            ->limit(5, 3)
            ->first();

        $expected =  "SELECT User.id AS number, User.name, Profile.group FROM users AS User";
        $expected .= " JOIN profiles AS Profile ON User.profile_id = Profile.id";
        $expected .= " WHERE name LIKE '%fsilva%' OR name LIKE '%other name%'";
        $expected .= " ORDER BY name ASC";
        $expected .= " LIMIT 1";

        $this->assertEquals($expected, MysqlTest::$sql);

        $this->assertEquals(5, $this->query->getLimit());
        $this->assertEquals((5 * (3 - 1)), $this->query->getOffset());


        $all = $this->query->all();

        $expected =  "SELECT User.id AS number, User.name, Profile.group FROM users AS User";
        $expected .= " JOIN profiles AS Profile ON User.profile_id = Profile.id";
        $expected .= " WHERE name LIKE '%fsilva%' OR name LIKE '%other name%'";
        $expected .= " ORDER BY name ASC";
        $expected .= " LIMIT 10, 5";

        $this->assertEquals($expected, MysqlTest::$sql);

        $this->assertEquals(1, count($all));
        $expected = array(
            'User' => array(
                'id' => 1,
                'name' => 'fsilva',
                'group' => 'admin'
            )
        );
        $this->assertContains($expected, $all);

        MysqlTest::$fail = true;
        $this->query->first();
    }

    /**
     * Testing count 
     * @test
     */
    public function queryCount()
    {
        MysqlTest::$mode = 'count';
        $rows = $this->query
            ->from('users AS User', array('User.id' => 'number', 'User.name'))
            ->where('name LIKE ?', '%fsilva%')
            ->orWhere('name LIKE ?', '%other name%')
            ->order('name', 'ASC')
            ->join('profiles AS Profile', 'User.profile_id = Profile.id', array('Profile.group'))
            ->limit(5, 3)
            ->count();
        $this->assertEquals(3, $rows);

        $expected =  "SELECT COUNT(1) AS rows FROM users AS User";
        $expected .= " JOIN profiles AS Profile ON User.profile_id = Profile.id";
        $expected .= " WHERE name LIKE '%fsilva%' OR name LIKE '%other name%' LIMIT 1";

        $this->assertEquals($expected, MysqlTest::$sql);
    }

    /**
     * Testing save method.
     * @test
     * @expectedException \Slick\Database\Exception\InvalidSqlException
     */
    public function querySave()
    {
        $data = array('name' => 'test');
        $id = $this->query->from('users')->save($data);
        $this->assertEquals(30, $id);
        $expected = "INSERT INTO users (`name`) VALUES ('test')";
        $this->assertEquals($expected, MysqlTest::$sql);

        $id = $this->query
            ->from('users')
            ->where('id = ?', 10)
            ->limit(1)
            ->save($data);
        $this->assertEquals(0, $id);
        $expected = "UPDATE users SET `name` = 'test' WHERE id = 10 LIMIT 1";
        $this->assertEquals($expected, MysqlTest::$sql);

        MysqlTest::$fail = true;
        $this->query->save($data);
    }

    /**
     * Testing delete
     * @test
     * @expectedException \Slick\Database\Exception\InvalidSqlException
     */
    public function queryDelete()
    {
        $rows = $this->query
            ->from('users')
            ->where('users.id = ?', 2)
            ->limit(2)
            ->delete();
        $this->assertEquals(2, $rows);
        $expected =  "DELETE FROM users WHERE users.id = 2 LIMIT 2";
        $this->assertEquals($expected, MysqlTest::$sql);
        MysqlTest::$fail = true;
        $this->query->delete();
    }

    

}