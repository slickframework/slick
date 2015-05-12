<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Adapter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\SqliteAdapter;

/**
 * Sqlite Adapter Test case
 *
 * @package Slick\Tests\Database\Adapter
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class SqliteAdapterTest extends TestCase
{

    /**
     * @var SqliteAdapter
     */
    protected $adapter;

    /**
     * Creates an in memory database connection
     */
    protected function setup()
    {
        parent::setUp();
        $this->adapter = new SqliteAdapter('sqlite::memory:');
    }

    /**
     * Clear all for next test
     */
    protected function tearDown()
    {
        $this->adapter = null;
        parent::tearDown();
    }

    /**
     * Sqlite don't have a name
     */
    public function testSchemaName()
    {
        $this->assertNull($this->adapter->getSchemaName());
    }

    public function testConnectionError()
    {
        $this->setExpectedException(
            'Slick\Database\Exception\ServiceException'
        );
        new SqliteAdapter(['file' => '/other/_databases/path/mydb.sq3']);
    }

    public function testQueryCleanup()
    {
        $query = 'First query;Second query';
        $count = 10;

        $statement = $this->getMockBuilder('PDOStatement')
            ->getMock();
        $statement->expects($this->exactly(2))
            ->method('execute')
            ->with()
            ->willReturn(null);
        $statement->expects($this->exactly(2))
            ->method('rowCount')
            ->willReturn($count);
        $statement->expects($this->exactly(2))
            ->method('fetchAll')
            ->with(\PDO::FETCH_NAMED, null, null)
            ->willReturn([]);

        $pdoMock = $this->getMockBuilder('Slick\Tests\Database\Fixtures\MockPDO')
            ->getMock();
        $pdoMock->expects($this->exactly(2))
            ->method('prepare')
            ->withConsecutive(
                [$this->equalTo('First query')],
                [$this->equalTo('Second query')]
            )
            ->willReturn($statement);

        $this->adapter->handler = $pdoMock;
        $this->adapter->execute($query);
    }
}