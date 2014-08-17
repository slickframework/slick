<?php

/**
 * Unique constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Database\Sql\Ddl\Constraint;
use Slick\Database\Sql\Ddl\Constraint\Unique;

/**
 * Unique constraint test case
 *
 * @package   Test\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UniqueTest extends \Codeception\TestCase\Test
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
