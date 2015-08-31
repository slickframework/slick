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

}
