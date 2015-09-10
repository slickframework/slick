<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Container;
use Slick\Di\Definition\Alias;
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\Value;

/**
 * Container test case
 *
 * @package Slick\Tests\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerTest extends TestCase
{

    /**
     * @var Container
     */
    protected $container;

    protected $className =
        'Slick\Tests\Di\DependencyInspector\Fixtures\InjectableClass';

    /**
     * Create the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new Container();
    }

    /**
     * Cleanup for next test
     */
    protected function tearDown()
    {
        $this->container = null;
        parent::tearDown();
    }

    /**
     * It should implement Interop\Container\ContainerInterface
     * @test
     */
    public function createAContainer()
    {
        $this->assertInstanceOf(
            'Interop\Container\ContainerInterface',
            $this->container
        );
    }

    /**
     * Should save value for later use
     * @test
     */
    public function registerAValue()
    {
        $bar = 'bar';
        $this->container->register('foo', $bar);
        $this->assertSame($bar, $this->container->get('foo'));
    }

    /**
     * Should return new instances of the resolved definition
     * @test
     */
    public function retrieveAPrototypedValue()
    {
        $value = new \stdClass();
        $definition = new Value(
            [
                'name' => 'prototypeDefinition',
                'value' => $value,
                'scope' => Scope::Prototype()
            ]
        );
        $this->assertEquals(
            $value,
            $this->container->register($definition)->get('prototypeDefinition')
        );
    }

    /**
     * Should throw not found exception
     * @test
     */
    public function retrieveUndefinedEntry()
    {
        $this->setExpectedException(
            'Interop\Container\Exception\NotFoundException'
        );
        $this->container->get('_unknown_container_entry');
    }

    /**
     * Should execute the callback
     * @test
     */
    public function registerACallback()
    {
        $this->container->register(
            'register-callback',
            $this->getCallback(),
            ['test']
        );
        $this->assertEquals(
            'test',
            $this->container->get('register-callback')->value
        );
    }

    /**
     * Runs definition resolve for every get call
     * @test
     */
    public function useCallbackAsPrototype()
    {
        $this->container->register(
            'register-callback-pt',
            $this->getCallback(),
            ['test'],
            Scope::Prototype()
        );
        $object = $this->container->get('register-callback-pt');
        $this->assertNotSame(
            $object,
            $this->container->get('register-callback-pt')
        );
        $this->assertEquals(
            $object,
            $this->container->get('register-callback-pt')
        );
    }

    /**
     * Should create and register a definition and return it
     * @test
     */
    public function makeObject()
    {
        $object = $this->container->make('stdClass');
        $this->assertSame($object, $this->container->get('stdClass'));
    }

    public function testMakeComplexObjectWithParams()
    {
        $object = $this->container->make(
            $this->className,
            [$this->container],
            Scope::Prototype()
        );
        $this->assertInstanceOf($this->className, $object);
    }

    public function testMakeComplexObject()
    {
        $object = $this->container->make($this->className);
        $this->assertInstanceOf($this->className, $object);
    }

    /**
     * should throw an invalid argument exception
     *
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     * @test
     */
    public function makeUnknownObject()
    {
        $this->container->make('_unknown_class_name');
    }

    /**
     * should retrieve the target entry definer in the alias definition object
     * @test
     */
    public function retrieveAnAlias()
    {
        $object = $this->container->make('stdClass');
        $this->container->register(
            new Alias(
                [
                    'name' => 'object',
                    'target' => 'stdClass'
                ]
            )
        );
        $this->assertSame($object, $this->container->get('object'));
    }

    private function getCallback()
    {
        $callback = function($value) {
            $obj = new \stdClass();
            $obj->value = $value;
            return $obj;
        };
        return $callback;
    }
}
