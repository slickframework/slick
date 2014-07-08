<?php

/**
 * Boolean column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;

use Slick\Database\Sql\Ddl\Column\Boolean;

/**
 * Boolean column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BooleanTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to create a Boolean column
     * @test
     */
    public function createBooleanColumn()
    {
        $col = new Boolean('active');
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);
        $this->assertEquals('active', $col->getName());
    }
}
