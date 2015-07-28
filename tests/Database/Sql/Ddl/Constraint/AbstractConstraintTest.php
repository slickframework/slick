<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Database\Sql\Ddl\Constraint;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Database\Sql\Ddl\Constraint\AbstractConstraint;

/**
 * Abstract constraint test case
 *
 * @package Slick\Tests\Database\Sql\Ddl\Constraint
 */
class AbstractConstraintTest extends TestCase
{

    /**
     * @var AbstractConstraint
     */
    protected $constraint;

    protected function setUp()
    {
        parent::setUp();
        $this->constraint = $this->getMockBuilder(
            'Slick\Database\Sql\Ddl\Constraint\AbstractConstraint'
        )
            ->setConstructorArgs(['testConstraint', ['name' => 'bar']])
            ->getMockForAbstractClass();
    }

    public function testGetName()
    {
        $this->assertEquals('bar', $this->constraint->getName());
    }

}
