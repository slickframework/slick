<?php

/**
 * Integer column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;

use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Integer;

/**
 * Integer column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class IntegerTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to create an integer column
     * @test
     */
    public function createIntegerColumn()
    {
        $col = new Integer('size');
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);

        $this->assertEquals(Size::NORMAL, (string) $col->getSize());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Integer', $col->setSize(Size::long()));
        $this->assertEquals(Size::LONG, (string) $col->getSize());

        $this->assertFalse($col->getAutoIncrement());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Integer', $col->setAutoIncrement(true));
        $this->assertTrue($col->getAutoIncrement());

        $this->assertEquals(0, $col->getDefault());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Integer', $col->setDefault(10));
        $this->assertEquals(10, $col->getDefault());

        $this->assertNull($col->getLength());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Integer', $col->setLength(8));
        $this->assertEquals(8, $col->getLength());

        $this->assertFalse($col->getNullable());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Integer', $col->setNullable(true));
        $this->assertTrue($col->getNullable());

    }
}
