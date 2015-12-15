<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\Definition;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Container;
use Slick\Di\Definition\Object as ObjectDefinition;
use Slick\Tests\Di\Fixtures\Dummy;

/**
 * Object definition Test case
 *
 * @package Slick\Tests\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectTest extends TestCase
{

    /**
     * @var ObjectDefinition
     */
    protected $definition;

    /**
     * sets the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->definition = new ObjectDefinition();
    }

    /**
     * Cleanup for next test
     */
    protected function tearDown()
    {
        $this->definition = null;
        parent::tearDown();
    }

    public function testSimpleObject()
    {
        $container = $this->getContainerMock();
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setContainer($container);
        $this->definition->setConstructArgs(['Unit test']);
        $object = $this->definition->resolve();
        $this->assertInstanceOf('Slick\Tests\Di\Fixtures\Dummy', $object);
        $this->assertEquals('Unit test', $object->value);
    }

    public function testPropertySet()
    {
        $container = $this->getContainerMock(['has']);
        $container->expects($this->any())
            ->method('has')
            ->willReturn(false);
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setContainer($container);
        $this->definition->setProperty('value', 'Other test');
        /** @var Dummy $object */
        $object = $this->definition->resolve();
        $this->assertEquals('Other test', $object->value);
    }

    public function testPrivateProperty()
    {
        $container = $this->getContainerMock(['has']);
        $container->expects($this->any())
            ->method('has')
            ->willReturn(false);
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setContainer($container);
        $this->definition->setProperty('name', 'Other test');
        /** @var Dummy $object */
        $object = $this->definition->resolve();
        $this->assertEquals('Other test', $object->getName());
    }

    public function testPrivatePropertyFromContainer()
    {
        $container = $this->getContainerMock(['has', 'get']);
        $container->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $container->expects($this->once())
            ->method('get')
            ->with('test-param')
            ->willReturn('Name from container');
        $container->register('test-param', 'Name from container');
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setContainer($container);
        $this->definition->setProperty('name', '@test-param');
        /** @var Dummy $object */
        $object = $this->definition->resolve();
        $this->assertEquals('Name from container', $object->getName());
    }

    /**
     * Should read class from instance
     * @test
     */
    public function getClassNameFromInstance()
    {
        $instance = new Dummy();
        $definition = new ObjectDefinition(['instance' => $instance]);
        $this->assertEquals(
            'Slick\Tests\Di\Fixtures\Dummy',
            $definition->getClassName()
        );
    }

    public function testConstructWithAliasArgs()
    {
        $container = $this->getContainerMock(['get', 'has']);
        $container->method('get')
            ->willReturn(new \stdClass());
        $container->method('has')
            ->willReturn(true);
        $this->definition
            ->className ='Slick\Tests\Di\Definition\CreatableObject';
        $this->definition->setContainer($container)
            ->setConstructArgs(['@test']);
        $object = $this->definition->resolve();
        $this->assertInstanceOf(
            'Slick\Tests\Di\Definition\CreatableObject',
            $object
        );
    }

    public function testMethod()
    {
        $container = $this->getContainerMock(['has']);
        $container->method('has')
            ->willReturn(false);
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setContainer($container);
        $this->definition->setMethod('setValue', ['last test']);
        /** @var Dummy $object */
        $object = $this->definition->resolve();
        $this->assertEquals('last test', $object->value);
    }

    /**
     * Should throw an exception
     *
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function setInvalidProperty()
    {
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setProperty('foo', 'bar');
    }

    /**
     * Should throw an exception
     *
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function setInvalidMethod()
    {
        $this->definition->className = 'Slick\Tests\Di\Fixtures\Dummy';
        $this->definition->setMethod('foo');
    }

    /**
     * @param array $methods
     * @return MockObject|Container
     */
    private function getContainerMock(array $methods = [])
    {
        /** @var Container|MockObject $container */
        $container = $this->getMockBuilder('Slick\Di\Container')
            ->setMethods($methods)
            ->getMock();
        return $container;
    }
}

class CreatableObject
{
    protected $object;

    public function __construct(\stdClass $object)
    {
        $this->object = $object;
    }
}