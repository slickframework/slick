<?php

/**
 * Alter test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Sql\Dialect;

use Codeception\Util\Stub;
use Slick\Database\Database,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\Ddl\Utility\Column;

/**
 * Alter test case
 *
 * @package   Test\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AlterTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Database\Query\Ddl\Alter
     */
    protected $_alter;

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
        $this->_alter = $db->connect()->ddlQuery()->alter('users');
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
                    'connector' => $this->_alter->query->connector
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
        $this->_alter->setQuery($query);
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_alter);
        parent::_after();
    }

    /**
     * Adding columns to an existing table
     * @test
     */
    public function addColumns()
    {
        $this->_mockQuery();
        $this->_alter
            ->addColumn(
                'delete',
                array(
                    'type' => Column::TYPE_BOOLEAN,
                    'default' => 0,
                    'notNull' => true
                )
            )
            ->addColumn(
                'updated',
                array(
                    'type' => Column::TYPE_DATETIME,
                    'notNull' => true
                )
            )
            ->execute();
        $expected = <<<EOS
ALTER TABLE `users`
    ADD COLUMN `delete` BOOLEAN NOT NULL DEFAULT '0',
    ADD COLUMN `updated` DATETIME NOT NULL
EOS;
        $this->assertEquals($expected, self::$_lastQuery);
    }

    /**
     * Add index to the table
     * @test
     */
    public function addIndex()
    {
        $this->_mockQuery();
        $this->_alter
            ->addIndex('name', array('type' => Index::UNIQUE))
            ->execute();
        $expected = <<<EOS
ALTER TABLE `users`
    ADD UNIQUE INDEX `name_idx` (`name` ASC)
EOS;
        $this->assertEquals($expected, self::$_lastQuery);
    }
}