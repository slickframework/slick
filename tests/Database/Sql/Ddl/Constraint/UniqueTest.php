<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Constraint;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Constraint\Unique;

/**
 * Unique constraint test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Constraint
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UniqueTest extends TestCase
{

    /**
     * Trying to create a unique constraint
     * @test
     */
    public function createUniqueConstraint()
    {
        $unique = new Unique('usernameUnique', ['column' => 'username']);
        $this->assertEquals('username', $unique->getColumn());
        $obj = $unique->setColumn('test');
        $this->assertInstanceOf('\Slick\Database\Sql\Ddl\Constraint\Unique', $obj);
        $this->assertEquals('test', $unique->getColumn());
    }
}
