<?php

/**
 * Text column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Column\Size;
use Slick\Database\Sql\Ddl\Column\Text;

/**
 * Text column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TextTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to create text column
     * @test
     */
    public function createTextColumn()
    {
        $col = new Text('email', ['size' => Size::TINY]);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);
        $this->assertEquals(Size::TINY, (string) $col->getSize());

        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Text', $col->setSize(Size::medium()));
        $this->assertEquals(Size::MEDIUM, $col->getSize()->getValue());

        $this->assertFalse($col->getNullable());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\Text', $col->setNullable(true));
        $this->assertTrue($col->getNullable());
    }
}
