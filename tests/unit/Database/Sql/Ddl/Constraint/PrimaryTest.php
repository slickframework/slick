<?php

/**
 * Primary constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Ddl\Constraint\Primary;

/**
 * Primary constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class PrimaryTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to create a primary constraint
     * @test
     */
    public function createPrimaryConstraint()
    {
        $primary = new Primary('UsersPk');
        $primary->setColumns('id');
        $expected = ['id'];
        $this->assertEquals($expected, $primary->getColumnNames());
        $this->assertEquals('UsersPk', $primary->getName());

        $primary->setColumns(['one', 'two']);
        $expected = ['one', 'two'];
        $this->assertEquals($expected, $primary->getColumnNames());

        $primary->setColumns('one, two');
        $this->assertEquals($expected, $primary->getColumnNames());
    }
}
