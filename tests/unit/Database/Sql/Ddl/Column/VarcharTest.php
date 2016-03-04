<?php

/**
 * Varchar column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Column;
use Slick\Database\Sql\Ddl\Column\Varchar;

/**
 * Varchar column test case
 *
 * @package   Test\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class VarcharTest extends \Codeception\TestCase\Test
{
    /**
     * Trying to create a varchar column
     * @test
     */
    public function createVarcharColumn()
    {
        $col = new Varchar('password', 128);
        $this->assertInstanceOf('Slick\Database\Sql\Ddl\Column\ColumnInterface', $col);
        $this->assertEquals(128, $col->getLength());
    }
}
