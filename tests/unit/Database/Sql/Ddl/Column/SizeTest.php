<?php

/**
 * Column size test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;

use Slick\Database\Sql\Ddl\Column\Size;

/**
 * Column size test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SizeTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to validate enumerable Size values
     * @test
     */
    public function validateSizeValues()
    {
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Size', Size::long());
        $this->assertEquals(Size::LONG, Size::long()->getValue());
        $this->assertEquals(Size::MEDIUM, Size::medium()->getValue());
        $this->assertEquals(Size::SMALL, Size::small()->getValue());
        $this->assertEquals(Size::TINY, Size::tiny()->getValue());
        $this->assertEquals(Size::NORMAL, Size::normal()->getValue());
        $this->assertEquals(Size::BIG, Size::big()->getValue());
    }
}