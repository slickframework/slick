<?php

/**
 * MysqlDDL test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl;

use Codeception\Util\Stub;
use Slick\Database\Database;

/**
 * MysqlDDL test case
 *
 * @package   Test\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlTest extends \Codeception\TestCase\Test
{

    /**
     * Create a Mysql DDL Query
     * @test
     */
    public function createMysqlDdlQuery()
    {
        $db = new Database(array('type' => 'mysql'));
        $create = $db->initialize()->ddlQuery()->create('users');
        $this->assertInstanceOf('Slick\Database\Query\Ddl\Create', $create);
    }

}