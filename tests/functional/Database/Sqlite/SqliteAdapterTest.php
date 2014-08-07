<?php

/**
 * SQLite functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sqlite;

use Slick\Database\Adapter;

/**
 * Mysql functional test case
 *
 * @package   Test\Database\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SqliteAdapterTest extends \Codeception\TestCase\Test
{

    /**
     * Creates a mysql adapter
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function createAdapter()
    {
        $dbFile = __DIR__ .'/myTest.db';
        $adapter = new Adapter([
            'driver' => 'Sqlite',
            'options' => [
                'file' => $dbFile
            ]
        ]);
        /** @var Adapter\MysqlAdapter $sqlite */
        $sqlite = $adapter->initialize();
        $this->assertInstanceOf('Slick\Database\Adapter\SqliteAdapter', $sqlite);

        $result = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table'");
        $this->assertTrue(count($result) == 0);
        $this->assertNull($sqlite->getSchemaName());

        if (is_file($dbFile)) {
            //copy($dbFile, __DIR__ .'/myTest-result.db');
            unlink($dbFile);
        }

        $adapter = new Adapter([
            'driver' => 'Sqlite',
            'options' => [
                'file' => '/_some/path/not/in/your/drive'
            ]
        ]);
        $adapter->initialize();
    }
}
