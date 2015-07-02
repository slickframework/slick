<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Column;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Column\Integer as IntegerColumn;
use Slick\Database\Sql\Ddl\Column\Size;

/**
 * Integer column test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Column
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class IntegerTest extends TestCase
{

    /**
     * @var IntegerColumn
     */
    protected $integer;

    protected function setUp()
    {
        parent::setUp();
        $this->integer = new IntegerColumn('age');
    }

    protected function tearDown()
    {
        $this->integer = null;
        parent::tearDown();
    }

    public function testInteger()
    {
        $this->assertEquals('age', $this->integer->getName());
    }

    public function testNullable()
    {
        $result = $this->integer->setNullable(true);
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Integer',
            $result
        );
        $this->assertTrue($this->integer->getNullable());
    }

    public function testDefault()
    {
        $result = $this->integer->setDefault(50);
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Integer',
            $result
        );
        $this->assertEquals(50, $this->integer->getDefault());
    }

    public function testLength()
    {
        $result = $this->integer->setLength(10);
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Integer',
            $result
        );
        $this->assertEquals(10, $this->integer->getLength());
    }

    public function testSize()
    {
        $result = $this->integer->setSize(Size::big());
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Integer',
            $result
        );
        $this->assertEquals(Size::BIG, $this->integer->getSize());
    }

    public function testAutoIncrement()
    {
        $result = $this->integer->setAutoIncrement(true);
        $this->assertInstanceOf(
            'Slick\Database\Sql\Ddl\Column\Integer',
            $result
        );
        $this->assertTrue($this->integer->getAutoIncrement());
    }
}
