<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Adapter;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\AbstractSql;
use Slick\Tests\Database\Fixtures\MockSql;

/**
 * Abstract Sql Test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractSqlTest extends TestCase
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var AbstractSql
     */
    protected $sut;

    protected function setup()
    {
        parent::setUp();
        $this->adapter = Adapter::create(
            [
            'driver' => 'Slick\Tests\Database\Fixtures\CustomAdapter'
            ]
        );
        $this->sut = new MockSql('tasks');
    }

    protected function tearDown()
    {
        $this->adapter = null;
        $this->sut = null;
        parent::tearDown();
    }

    public function testSettingAdapter()
    {
        $result = $this->sut->setAdapter($this->adapter);
        $this->assertInstanceOf('Slick\Database\Sql\AbstractSql', $result);
        $this->assertEquals($this->adapter, $result->getAdapter());
    }

    public function testTableName()
    {
        $this->assertEquals('tasks', $this->sut->getTable());
    }

    public function testGetParameters()
    {
        $this->assertEquals([], $this->sut->getParameters());
    }
}
