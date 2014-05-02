<?php

/**
 * Container builder test case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di;
use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;

/**
 * Container builder test case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class BuilderTest extends \Codeception\TestCase\Test
{

    /**
     * Create a container with container builder
     * @test
     */
    public function buildContainer()
    {
        $container = ContainerBuilder::buildContainer([]);
        $this->assertInstanceOf('Slick\Di\Container', $container);
        $definition = [
            '_foo' => 'foo',
            '_bar' => Definition::link('_foo'),
            '_baz' => Definition::factory(function(){
                    return 'baz';
                })
        ];

        $container2 = ContainerBuilder::buildContainer($definition);
        $this->assertSame($container, $container2);

        $this->assertEquals('foo', $container->get('_foo'));
        $this->assertEquals('foo', $container->get('_bar'));
        $this->assertEquals('baz', $container->get('_baz'));
    }
}