<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Schema;

/**
 * Database Schema Test case
 * @package Slick\Tests\Database
 */
class SchemaTest extends TestCase
{

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * Creates the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->schema = new Schema();
    }

    /**
     * Should implement the SchemaInterface
     *
     * @test
     */
    public function createSchema()
    {
        $this->assertInstanceOf(
            'Slick\Database\Schema\SchemaInterface',
            $this->schema
        );
    }

    /**
     * Should add table interface objects to tables list
     * @test
     */
    public function addingTables()
    {
        /** @var Schema\TableInterface|MockObject $table */
        $table = $this->getMockBuilder(
            'Slick\Database\Schema\TableInterface'
        )
            ->getMock();
        $table->expects($this->once())
            ->method('getName')
            ->willReturn('users');

        $this->schema->setTables([$table]);
        $expected = ['users' => $table];
        $this->assertEquals($expected, $this->schema->getTables());
    }

    /**
     * Should use magic setter from Slick\Common\Base
     * @test
     */
    public function setSchemaName()
    {
        $expected = 'test';
        $this->assertEquals(
            $expected,
            $this->schema->setName($expected)->getName()
        );
    }

    /**
     * Should accept adapter interface
     * @test
     */
    public function setAdapter()
    {
        /** @var AdapterInterface|MockObject $adapter */
        $adapter = $this->getMockBuilder(
            'Slick\Database\Adapter\AdapterInterface'
        )
            ->getMock();
        $this->schema->setAdapter($adapter);
        $this->assertEquals($adapter, $this->schema->getAdapter());
    }

    /**
     * Should iterate over the tables and concatenate the create statements
     * @test
     */
    public function getSqlStatements()
    {
        /** @var Schema\TableInterface|MockObject $table */
        $table = $this->getMockBuilder(
            'Slick\Database\Schema\TableInterface'
        )
            ->getMock();
        $table->expects($this->once())
            ->method('setSchema')
            ->with($this->isInstanceOf('Slick\Database\Schema\SchemaInterface'))
            ->willReturnSelf();
        $table->expects($this->once())
            ->method('getCreateStatement')
            ->willReturn('test');
        /** @var AdapterInterface|MockObject $adapter */
        $adapter = $this->getMockBuilder(
            'Slick\Database\Adapter\AdapterInterface'
        )
            ->getMock();
        $this->schema->setAdapter($adapter);
        $this->schema->addTable($table);
        $expected = 'test';
        $this->assertEquals($expected, $this->schema->getCreateStatement());
    }

    /**
     * Cleanup for next test
     */
    protected function tearDown()
    {
        $this->schema = null;
        parent::tearDown();
    }
}
