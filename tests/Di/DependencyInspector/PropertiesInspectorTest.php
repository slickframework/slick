<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Container;
use Slick\Di\Definition\Object as ObjectDefinition;
use Slick\Di\Definition\ObjectDefinitionInterface;
use Slick\Di\DependencyInspector\Parameter;
use Slick\Di\DependencyInspector\PropertiesInspector;

/**
 * PropertiesInspector test case
 *
 * @package Slick\Tests\Di\DependencyInspector
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PropertiesInspectorTest extends TestCase
{

    /**
     * @var PropertiesInspector
     */
    protected $inspector;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ObjectDefinitionInterface
     */
    protected $definition;

    protected $className =
        'Slick\Tests\Di\DependencyInspector\Fixtures\PropertyInjectable';

    protected $failClassName =
        'Slick\Tests\Di\DependencyInspector\Fixtures\MethodFailing';

    /**
     * Sets up the SUT inspector object
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
        $this->inspector = new PropertiesInspector(
            [
                'definition' => $this->definition
            ]
        );
    }

    protected function tearDown()
    {
        $this->container = $this->definition = $this->inspector = null;
        parent::tearDown();
    }

    public function testInjectVarDefined()
    {
        $this->assertInstanceOf(
            'Slick\Di\DependencyInspector\Parameter',
            $this->definition->getProperties()['foo']
        );
    }

    public function testInjectDefined()
    {
        $this->assertInstanceOf(
            'Slick\Di\DependencyInspector\Parameter',
            $this->definition->getProperties()['bar']
        );
    }

    public function testEntryNotFound()
    {
        $this->setExpectedException('Slick\Di\Exception\NotFoundException');
        $this->definition = new ObjectDefinition(
            [
                'className' => $this->failClassName,
                'container' => $this->container
            ]
        );
        $this->inspector->setDefinition($this->definition);
    }
}
