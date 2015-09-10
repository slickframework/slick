<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\ContainerBuilder;

/**
 * ContainerBuilder test
 *
 * @package Slick\Tests\Di
 * @author  Filipe Silva
 */
class ContainerBuilderTest extends TestCase
{

    /**
     * @var ContainerBuilder
     */
    protected $builder;

    /**
     * Set the SUT builder object
     */
    protected function setUp()
    {
        parent::setUp();
        $file = __DIR__ .'/Fixtures/definitions.php';
        $this->builder = new ContainerBuilder($file);
    }

    public function testBuildContainer()
    {
        $container = $this->builder->getContainer();
        $this->assertInstanceOf('Slick\Di\Container', $container);
    }

    public function testResolveSimple()
    {
        $expected = 'bar';
        $container = $this->builder->getContainer();
        $this->assertEquals($expected, $container->get('def.foo'));
    }

    public function testOneContainerPerBuilder()
    {
        $other = $container = $this->builder->getContainer();
        $container = $container = $this->builder->getContainer();
        $this->assertSame($container, $other);
    }

    public function testCreationError()
    {
        $this->setExpectedException(
            'Slick\Di\Exception\InvalidArgumentException'
        );
        new ContainerBuilder(new \stdClass());
    }


}
