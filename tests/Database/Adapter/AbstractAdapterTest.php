<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Adapter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Dialect;
use Slick\Tests\Database\Fixtures\CustomAdapter;
use Slick\Tests\Database\Fixtures\CustomQuery;

/**
 * Abstract Adapter Test case
 *
 * @package Slick\Tests\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmal.com>
 */
class AbstractAdapterTest extends TestCase
{
    /**
     * @var CustomAdapter
     */
    protected $adapter;

    /**
     * Creates the adapter for tests
     */
    protected function setup()
    {
        parent::setUp();
        $this->adapter = new CustomAdapter();
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
     * Handles disconnect
     */
    public function testHandlerDisconnect()
    {
        $this->adapter->disconnect();
        $this->assertFalse($this->adapter->isConnected());
        $this->assertNull($this->adapter->getHandler());
    }

    /**
     * Initialization should return current object
     */
    public function testInitialization()
    {
        $this->assertSame($this->adapter, $this->adapter->initialize());
    }

    /**
     * Retrieve the logger object
     */
    public function testLoggerCreation()
    {
        $this->assertEquals(
            "Database",
            $this->adapter->getLogger()->getName()
        );
    }

    public function testTransactionSequence()
    {
        $stub = $this->getPDOMock();

        $stub->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);
        $stub->expects($this->once())
            ->method('commit')
            ->willReturn(false);
        $stub->expects($this->once())
            ->method('rollBack')
            ->willReturn(true);

        $this->adapter->handler = $stub;
        $this->adapter->beginTransaction();

        if (!$this->adapter->commit()) {
            $this->assertTrue($this->adapter->rollBack());
        }
    }

    public function testDialect()
    {
        $this->assertEquals(Dialect::STANDARD, $this->adapter->getDialect());
    }

    public function testQueryDDLExecution()
    {
        $query = 'Update something...';
        $count = 10;

        $statement = $this->getMockBuilder('PDOStatement')
            ->getMock();
        $statement->expects($this->once())
            ->method('execute')
            ->with()
            ->willReturn(null);
        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn($count);
        $statement->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_NAMED, null, null)
            ->willReturn([]);

        $pdoMock = $this->getPDOMock();
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with($query)
            ->willReturn($statement);

        $this->adapter->handler = $pdoMock;
        $this->adapter->execute($query);
        $this->assertEquals($count, $this->adapter->getAffectedRows());
    }

    public function testQueryExecution()
    {
        $query = 'Update something...';
        $count = 2;
        $data = [
            ['id' => 1, 'name' => 'test'],
            ['id' => 2, 'name' => 'other test'],
        ];

        $statement = $this->getMockBuilder('PDOStatement')
            ->getMock();
        $statement->expects($this->once())
            ->method('execute')
            ->with([])
            ->willReturn(null);
        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn($count);
        $statement->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_NAMED, null, null)
            ->willReturn($data);

        $pdoMock = $this->getPDOMock();
        $pdoMock->expects($this->once())
            ->method('prepare')
            ->with($query)
            ->willReturn($statement);

        $this->adapter->handler = $pdoMock;
        $result = $this->adapter->query($query);
        $this->assertEquals($data, $result->asArray());
    }

    public function testLastInsertedId()
    {
        $lastId = 1232;
        $pdo = $this->getPDOMock();
        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->with()
            ->willReturn($lastId);
        $this->adapter->handler = $pdo;
        $this->assertEquals($lastId, $this->adapter->getLastInsertId());
    }

    public function testLastError()
    {
        $error = [
            0 => '42000',
            1 => '1072',
            2 => "Key column 'test' doesn't exist in table"
        ];
        $pdo = $this->getPDOMock();
        $pdo->expects($this->once())
            ->method('errorInfo')
            ->willReturn($error);
        $this->adapter->handler = $pdo;
        $this->assertEquals($error[2], $this->adapter->getLastError());
    }

    public function testExceptionHandling()
    {
        $pdo = $this->getPDOMock();
        $pdo->expects($this->once())
            ->method('prepare')
            ->will($this->throwException(new \PDOException("Error")));
        $this->adapter->handler = $pdo;
        $this->setExpectedException('Slick\Database\Exception\SqlQueryException');
        $this->adapter->query(new CustomQuery());
    }

    public function testInvalidQueryUsage()
    {
        $pdo = $this->getPDOMock();
        $this->setExpectedException(
            'Slick\Database\Exception\InvalidArgumentException'
        );
        $this->adapter->handler = $pdo;
        $this->adapter->query(new \stdClass());
    }

    public function testConnectionBeforeAction()
    {
        $this->adapter->disconnect();
        $this->setExpectedException(
            'Slick\Database\Exception\ServiceException'
        );
        $this->adapter->query('test');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPDOMock()
    {
        return $this
            ->getMockBuilder('Slick\Tests\Database\Fixtures\MockPDO')
            ->getMock();
    }

}
