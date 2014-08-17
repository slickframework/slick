<?php

/**
 * DateTime column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;

use Slick\Database\Sql\Ddl\Column\DateTime;

/**
 * DateTime column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DateTimeTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to create a date time column
     * @test
     */
    public function createDateTimeColumn()
    {
        $col = new DateTime('created',['nullable' => true]);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);
        $this->assertTrue($col->getNullable());
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col->setNullable(false));
        $this->assertfalse($col->getNullable());
    }
}
