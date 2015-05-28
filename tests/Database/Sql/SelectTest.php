<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Select;
use Slick\Tests\Database\Fixtures\CustomAdapter;

/**
 * SQL Select query object test case
 *
 * @package Slick\Tests\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectTest extends TestCase
{

    /**
     * @var Select
     */
    protected $select;

    protected function setup()
    {
        parent::setUp();
        $this->select = new Select('tasks');
        $this->select->setAdapter(new CustomAdapter());
    }

    protected function tearDown()
    {
        $this->select = null;
        parent::tearDown();
    }

    public function testAlias()
    {
        $this->assertEquals('tasks', $this->select->getAlias());
    }

    public function testSimpleQuery()
    {
        $this->select->where(['id = :id' => [':id' => 1]])
            ->order('tasks.created DESC')
            ->setDistinct();
        $expected  = 'SELECT DISTINCT * FROM tasks ';
        $expected .= 'WHERE id = :id ';
        $expected .= 'ORDER BY tasks.created DESC';
        $this->assertEquals($expected, $this->select->getQueryString());
        $this->assertEquals([':id' => 1], $this->select->getParameters());
    }

    public function testSimpleLimit()
    {
        $expected  = 'SELECT * FROM tasks FETCH FIRST 10 ROWS ONLY';
        $this->select->limit(10);
        $this->assertEquals($expected, $this->select->getQueryString());
    }

    public function testOffsetLimit()
    {
        $expected  = 'SELECT * FROM tasks OFFSET 2 ROWS FETCH FIRST 10 ROWS ONLY';
        $this->select->limit(10, 2);
        $this->assertEquals($expected, $this->select->getQueryString());
    }
}
