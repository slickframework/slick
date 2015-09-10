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
use Slick\Di\DependencyInspector\ConstructorInspector;

/**
 * ConstructorInspector Test case
 *
 * @package Slick\Tests\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConstructorInspectorTest extends TestCase
{

    /**
     * @var ConstructorInspector
     */
    protected $inspector;

    /**
     * @var ObjectDefinition
     */
    protected $definition;

    protected $className =
        'Slick\Tests\Di\DependencyInspector\Fixtures\InjectableClass';
    protected $failClassName =
        'Slick\Tests\Di\DependencyInspector\Fixtures\FailingInjection';

    protected $container;

    /**
     * Creates the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new Container();
        $this->definition = new ObjectDefinition(
            [
                'className' => $this->className,
                'container' => $this->container
            ]
        );
        $this->inspector = new ConstructorInspector(
            [
                'definition' => $this->definition,

            ]
        );
    }

    /**
     * @test
     */
    public function getConstructorArguments()
    {
        $args = $this->definition->constructArgs;
        $first = reset($args);
        $this->assertSame($first, $this->container);
    }

    public function testFailInjection()
    {
        $this->definition = new ObjectDefinition(
            [
                'className' => $this->failClassName,
                'container' => $this->container
            ]
        );
        $this->inspector->setDefinition($this->definition);
        $this->assertFalse($this->inspector->isSatisfiable());
    }

    public function testNoConstructorClass()
    {
        $this->definition = new ObjectDefinition(
            [
                'className' => 'Slick\Tests\Di\DependencyInspector\Fixtures\MethodFailing',
                'container' => $this->container
            ]
        );
        $this->inspector->setDefinition($this->definition);
        $this->assertTrue($this->inspector->isSatisfiable());
    }
}
