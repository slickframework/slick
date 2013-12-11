<?php

/**
 * Mysql parser test case
 *
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Definition\Parser;

use Codeception\Util\Stub;
use Slick\Database\RecordList,
    Slick\Database\Definition\Parser\Mysql;

/**
 * Mysql parser test case
 *
 * @package   Test\Database\Definition\Parser
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    protected $_mysql;

    protected function _before()
    {
        $data = new \StdClass();
        $data->Table = 'users';
        $prop = 'Create Table';
        $data->$prop = <<<EOS
CREATE TABLE `users` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL COMMENT 'Test',
 `full_name` tinytext,
 `active` tinyint(1) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 UNIQUE KEY `name_idx` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
EOS;
        $recordList = new RecordList(array($data));
        $this->_mysql = new Mysql(array('data' => $recordList));
    }

    protected function _after()
    {
        unset($this->_mysql);
    }

    /**
     * Retrive columns
     * @test
     */
    public function retrieveColumns()
    {
        $columns = $this->_mysql->getColumns();
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Utility\ElementList', $columns);
        $id = $columns->findByName('id');
        $this->assertTrue($id->isPrimaryKey());
    }

}