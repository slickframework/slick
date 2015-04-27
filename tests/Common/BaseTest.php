<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Class BaseTest
 *
 * @package Slick\tests\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class BaseTest extends TestCase
{
    /**
     * @var Fixtures\BaseTest
     */
    protected $base;

    protected function setup()
    {
        parent::setUp();
        $this->base = new Fixtures\BaseTest(
            [
                'name' => 'Filipe Silva',
                'mail' => 'silvam.filipe@gmail.com'
            ]
        );
    }

    protected function tearDow()
    {
        $this->base = null;
        parent::tearDown();
    }

    public function testAssignValuesOnConstructor()
    {
        $this->assertEquals('Filipe Silva', $this->base->name);
    }

    public function testChangePropertyValue()
    {
        $this->base->mail = 'Other mail';
        $this->assertEquals('Other mail', $this->base->getMail());
    }

    public function testExceptionOnUnknownMethod()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\UnimplementedMethodCallException"
        );
        $this->base->foo();
    }

    public function testCheckPropertyExistence()
    {
        $this->assertTrue(isset($this->base->name));
    }

    public function testUnExistentPropertyReturnNull()
    {
        $this->assertNull($this->base->foo);
    }

    public function testExceptionOnReadWriteOnlyProperty()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\WriteOnlyException"
        );
        $this->base->state;
    }

    public function testExceptionOnWriteReadOnlyProperty()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\ReadOnlyException"
        );
        $this->base->fullName = 'Hey';
    }

    public function testExceptionOnWriteTOUnknownProperty()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\UndefinedPropertyException"
        );
        $this->base->foo = 'Hey';
    }

    public function testIsCheckerReturnsBoolean()
    {
        $this->assertTrue(is_bool($this->base->isName()));
    }

    public function testIsCheckerExceptionOnWriteOnlyProp()
    {
        $this->setExpectedException(
            "Slick\\Common\\Exception\\WriteOnlyException"
        );
        $this->base->isState();
    }

    public function testIsCheckerFalseOnUnknown()
    {
        $this->assertTrue(false === $this->base->isFoo());
    }
}
