<?php

/**
 * Delete statment test case
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
    Slick\Database\Query\Sql\Delete;

/**
 * Delete statment test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DeleteTest extends \Codeception\TestCase\Test
{

   /**
     * @var \Slick\Database\Connector\Mysql
     */
    protected $_connector = null;

    /**
     * @var \Slick\Database\Query\Sql\Delete
     */
    protected $_delete = null;

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
        $this->_delete = $this->_connector->query()->delete('users');
        unset($db);

    }

    /**
     * clean up for next test
     */
    protected function _after()
    {
        $this->_connector = null;
        $this->_delete = null;
        parent::_after();
    }

    /**
     * Insert data
     * @test
     */
    public function deleteData()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'SQLite',
                    'connector' => $this->_delete->query->connector
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
        $this->_delete->setQuery($query);

         $this->assertInstanceOf(
            'Slick\Database\Query\Sql\Delete',
            $this->_delete->where(array('id = :id' => array(':id' => 30)))
        );
        $this->_delete->execute();
        $this->assertEquals(
            array(
                ':id' => 30
            ),
            self::$_usedParams
        );
        $expected = <<<EOT
DELETE FROM users
WHERE id = :id
EOT;
        $this->assertEquals($expected, self::$_lastQuery);
    }

}