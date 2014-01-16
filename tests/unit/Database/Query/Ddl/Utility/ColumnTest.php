<?php

/**
 * Column test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Database\Query\Ddl\Utility;

use Codeception\Util\Stub;
use Slick\Database\Query\Ddl\Utility\Column;

/**
 * Column test case
 *
 * @package   Test\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ColumnTest extends \Codeception\TestCase\Test
{

    /**
     * Check the outcome from column to string
     * 
     * @test
     */
    public function verifyTypeText()
    {
        $column = new Column(array('name' => 'test'));
        $this->assertEquals('TEXT', $column->typeAsString());

        $column->setType(Column::TYPE_INTEGER);
        $this->assertEquals('INTEGER', $column->typeAsString());

        $column->setType(Column::TYPE_FLOAT);
        $this->assertEquals('FLOAT', $column->typeAsString());

        $column->setType(Column::TYPE_VARCHAR);
        $this->assertEquals('VARCHAR', $column->typeAsString());

        $column->setType(Column::TYPE_BLOB);
        $this->assertEquals('BLOB', $column->typeAsString());

        $column->setType(Column::TYPE_BOOLEAN);
        $this->assertEquals('BOOLEAN', $column->typeAsString());

        $column->setType(Column::TYPE_DATETIME);
        $this->assertEquals('DATETIME', $column->typeAsString());

        $expected = "'test' DATETIME NORMAL NULL";
        $this->assertEquals($expected, (string) $column);
    }

}