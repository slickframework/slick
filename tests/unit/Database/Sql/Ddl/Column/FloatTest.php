<?php

/**
 * Float column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;

use Slick\Database\Sql\Ddl\Column\Float;

/**
 * Float column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FloatTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to create a float column
     * @test
     */
    public function createFloatColumn()
    {
        $col = new Float('price', 13, 3);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);
        $this->assertEquals(13, $col->getDigits());
        $this->assertEquals(3, $col->getDecimal());
    }
}
