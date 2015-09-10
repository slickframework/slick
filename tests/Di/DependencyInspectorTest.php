<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Container;
use Slick\Di\DependencyInspector;
use Slick\Tests\Di\DependencyInspector\Fixtures\InjectableClass;

/**
 * DependencyInspector test case
 *
 * @package Slick\Tests\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DependencyInspectorTest extends TestCase
{

    /**
     * @var DependencyInspector
     */
    protected $inspector;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Create the SUT inspector object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new Container();
        $this->container->register(
            'Slick\Di\DependencyInspector\Parameter',
            function() {
                return new DependencyInspector\Parameter(['name' => 'test']);
            }
        );
        $this->inspector = new DependencyInspector(
            $this->container,
            'Slick\Tests\Di\DependencyInspector\Fixtures\InjectableClass'
        );
    }

    public function testResolveDefinition()
    {
        $definition = $this->inspector->getDefinition();
        /** @var InjectableClass $object */
        $object = $definition->resolve();
        $this->assertSame($this->container, $object->getContainer());
    }

    public function testResolvePropertyDefinition()
    {
        $definition = $this->inspector->getDefinition();
        /** @var InjectableClass $object */
        $object = $definition->resolve();
        $this->assertSame($this->container, $object->getBar());
    }

    public function testClassNotFound()
    {
        $this->setExpectedException('Slick\Di\Exception\InvalidArgumentException');
        new DependencyInspector($this->container, '\my\test\missing\class');
    }
}
