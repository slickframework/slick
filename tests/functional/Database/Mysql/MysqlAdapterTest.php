<?php

/**
 * Mysql functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Mysql;

use Slick\Configuration\Driver\Ini;
use Slick\Database\Adapter;

/**
 * Mysql functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlAdapterTest extends \Codeception\TestCase\Test
{

    /**
     * Creates a mysql adapter
     * @test
     */
    public function createAdapter()
    {
        $cfg = new Ini(['file' => __DIR__ .'/database.ini']);
        $adapter = new Adapter($cfg->get('default'));
        /** @var Adapter\MysqlAdapter $mysql */
        $mysql = $adapter->initialize();
        $this->assertInstanceOf('Slick\Database\Adapter\MysqlAdapter', $mysql);

        $result = $mysql->query('SHOW TABLES');
        $this->assertTrue(count($result) == 0);
    }
}