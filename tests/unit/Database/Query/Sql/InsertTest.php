<?php

/**
 * Insert statment test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Sql;

use Codeception\Util\Stub;
use Slick\Database\Database,
    Slick\Database\Query\Sql\Insert;

/**
 * Insert statment test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InsertTest extends \Codeception\TestCase\Test
{

   /**
     * @var \Slick\Database\Connector\Mysql
     */
    protected $_connector = null;

    /**
     * @var \Slick\Database\Query\Sql\Insert
     */
    protected $_insert = null;

    protected static $_lastQuery;
    protected static $_usedParams = array();

    /**
     * Creates the STU object
     */
    protected function _before()
    {
        parent::_before();
        $db = new Database(
            array(
                'type' => 'sqlite',
            )
        );
        $this->_connector = $db->initialize();
        $this->_insert = $this->_connector->query()->insert('users');
        unset($db);

    }

    /**
     * clean up for next test
     */
    protected function _after()
    {
        $this->_connector = null;
        $this->_insert = null;
        parent::_after();
    }

    /**
     * Insert data
     * @test
     */
    public function insertData()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'SQLite',
                    'connector' => $this->_insert->query->connector
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
        $this->_insert->setQuery($query);

        $this->assertInstanceOf(
            'Slick\Database\Query\Sql\Insert',
            $this->_insert->set(
                array(
                    'name' => 'Filipe',
                    'email' => 'silvam.filipe@gmail.com'
                )
            )
        );
        $this->_insert->save();
        $this->assertEquals(
            array(
                ':name' => 'Filipe',
                ':email' => 'silvam.filipe@gmail.com'
            ),
            self::$_usedParams
        );
        $expected = <<<EOT
INSERT INTO users (`name`, `email`)
VALUES (:name, :email)
EOT;
        $this->assertEquals($expected, self::$_lastQuery);
    }

}