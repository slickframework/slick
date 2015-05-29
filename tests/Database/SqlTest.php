<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * SQL object factory test case
 *
 * @package Slick\Tests\Database
 * @author  Filipe Silva Filipe Silva <silvam.filipe@sata.pt>
 */
class SqlTest extends TestCase
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    protected function setUp()
    {
        parent::setUp();
        $this->adapter = new CustomAdapter();
    }

    protected function tearDown()
    {
        $this->adapter = null;
        parent::tearDown();
    }

    public  function testCreateSelectQuery()
    {
        $sql = Sql::createSql($this->adapter)->select('tasks');
        $this->assertInstanceOf("Slick\\Database\\Sql\\Select", $sql);
    }

    public  function testCreateUpdateQuery()
    {
        $sql = Sql::createSql($this->adapter)->update('tasks');
        $this->assertInstanceOf("Slick\\Database\\Sql\\Update", $sql);
    }

    public  function testCreateInsertQuery()
    {
        $sql = Sql::createSql($this->adapter)->insert('tasks');
        $this->assertInstanceOf("Slick\\Database\\Sql\\Insert", $sql);
    }

    public  function testCreateDeleteQuery()
    {
        $sql = Sql::createSql($this->adapter)->delete('tasks');
        $this->assertInstanceOf("Slick\\Database\\Sql\\Delete", $sql);
    }
}
