<?php

/**
 * Update statment test case
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
    Slick\Database\Query\Sql\Update;

/**
 * Update statment test case
 *
 * @package   Test\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UpdateTest extends \Codeception\TestCase\Test
{

   /**
     * @var \Slick\Database\Connector\Mysql
     */
    protected $_connector = null;

    /**
     * @var \Slick\Database\Query\Sql\Update
     */
    protected $_update = null;

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
        $this->_update = $this->_connector->query()->update('users');
        unset($db);

    }

    /**
     * clean up for next test
     */
    protected function _after()
    {
        $this->_connector = null;
        $this->_update = null;
        parent::_after();
    }

    /**
     * Insert data
     * @test
     */
    public function updateData()
    {
        $query = Stub::construct(
            'Slick\Database\Query\Query',
            array(
                array(
                    'dialect' => 'SQLite',
                    'connector' => $this->_update->query->connector
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
        $this->_update->setQuery($query);

        $this->assertInstanceOf(
            'Slick\Database\Query\Sql\Update',
            $this->_update->set(
                array(
                    'name' => 'Filipe',
                    'email' => 'silvam.filipe@gmail.com'
                )
            )
        );
         $this->assertInstanceOf(
            'Slick\Database\Query\Sql\Update',
            $this->_update->where(array('id = :id' => array(':id' => 30)))
        );
        $this->_update->save();
        $this->assertEquals(
            array(
                ':name' => 'Filipe',
                ':email' => 'silvam.filipe@gmail.com',
                ':id' => 30
            ),
            self::$_usedParams
        );
        $expected = <<<EOT
UPDATE users SET (`name`=:name, `email`=:email)
WHERE id = :id
EOT;
        $this->assertEquals($expected, self::$_lastQuery);
    }

}