<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Container;
use Slick\Di\Definition\Object as ObjectDefinition;
use Slick\Di\DependencyInspector\MethodsInspector;
use Slick\Di\DependencyInspector\Parameter;

/**
 * MethodsInspector Test case
 *
 * @package Slick\Tests\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MethodsInspectorTest extends TestCase
{

    /**
     * @var MethodsInspector
     */
    protected $inspector;

    /**
     * @var ObjectDefinition
     */
    protected $definition;

    protected $className =
        'Slick\Tests\Di\DependencyInspector\Fixtures\InjectableClass';
    protected $methodFailingClassName =
        'Slick\Tests\Di\DependencyInspector\Fixtures\MethodFailing';

    protected $container;

    /**
     * Creates the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new Container();
        $this->container->register(
            'Slick\Di\DependencyInspector\Parameter',
            function() {
                return new Parameter(['name' => 'test']);
            }
        );
        $this->definition = new ObjectDefinition(
            [
                'className' => $this->className,
                'container' => $this->container
            ]
        );
        $this->inspector = new MethodsInspector(
            [
                'definition' => $this->definition,

            ]
        );
    }

    protected function tearDown()
    {
        $this->inspector = null;
        $this->definition = null;
        $this->container = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNormalDependencyMethod()
    {
        $methods = $this->definition->getMethods();
        $this->assertTrue(array_key_exists('setObject', $methods));
    }

    public function testMethodIgnored()
    {
        $methods = $this->definition->getMethods();
        $this->assertFalse(array_key_exists('setSomeThing', $methods));
    }

    public function testNotASetter()
    {
        $methods = $this->definition->getMethods();
        $this->assertFalse(array_key_exists('addObject', $methods));
    }

    public function testWithDefaultParameter()
    {
        $methods = $this->definition->getMethods();
        $this->assertTrue(array_key_exists('setWithDefault', $methods));
    }

    public function testIgnoreAnnotation()
    {
        $methods = $this->definition->getMethods();
        $this->assertFalse(array_key_exists('setIgnoredAnnotation', $methods));
    }

    public function testInjectAnnotation()
    {
        $methods = $this->definition->getMethods();
        $this->assertFalse(array_key_exists('injectIt', $methods));
    }

    public function testThrowExceptionWhenInjecting()
    {
        $this->setExpectedException('Slick\Di\Exception\NotFoundException');
        $this->definition = new ObjectDefinition(
            [
                'className' => $this->methodFailingClassName,
                'container' => $this->container
            ]
        );
        $this->inspector->setDefinition($this->definition);
    }
}
