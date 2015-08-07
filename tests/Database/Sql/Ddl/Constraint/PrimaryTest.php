<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Constraint;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Constraint\Primary;

/**
 * Class PrimaryTest
 * @package Slick\Tests\Database\Sql\Ddl\Constraint
 */
class PrimaryTest extends TestCase
{

    public function testColumnCreation()
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
