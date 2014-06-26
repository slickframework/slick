<?php

/**
 * Mysql database adapter test case
 *
 * @package   Test\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Adapter;

use Slick\Database\Adapter\MysqlAdapter;
use PDO;

/**
 * Mysql database adapter test case
 *
 * @package   Test\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlAdapterTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to connect with a MySql Adapter
     * @test
     * @expectedException \Slick\Database\Exception\ServiceException
     */
    public function connectWithMysqlAdapter()
    {
        $adapter = new MysqlAdapter(['handlerClass' => 'Database\Adapter\Handler']);
        $this->assertTrue($adapter->isConnected());
        $this->assertInstanceOf('Slick\Database\Adapter\MysqlAdapter', $adapter);
        $this->assertInstanceOf('Slick\Database\Adapter\AdapterInterface', $adapter);
        $this->assertInstanceOf('Slick\Database\Adapter\AbstractAdapter', $adapter);

        $adapter = new MysqlAdapter(['username' => '_unknown_']);
        //$adapter->connect();
    }

}

/**
 * Class Handler for database adapter tests
 *
 * @package Database
 */
class Handler extends PDO
{
    /**
     * @var bool
     */
    public static $connectionError = false;

    /**
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param mixed $options
     * @throws \PDOException
     */
    public function __construct($dsn, $username, $passwd, $options)
    {
        if (static::$connectionError) {
            throw new \PDOException("Error connecting to database.");
        }

        parent::__construct('sqlite::memory:');
    }

}