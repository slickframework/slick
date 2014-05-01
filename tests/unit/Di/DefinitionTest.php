<?php

/**
 * Definition test case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di;

use Codeception\Util\Stub;
use Slick\Di\Definition;

/**
 * Definition test case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionTest extends \Codeception\TestCase\Test
{

    /**
     * Create a container definition array
     * @test
     */
    public function createContainerDefinitions()
    {
        $definitions = [
            'link' => Definition::link('otherLink'),
            'factory' => Definition::factory(function(){return true;})
        ];

        $this->assertInstanceOf('Slick\Di\Definition\ObjectDefinition\EntryReference', $definitions['link']);
        $this->assertEquals('otherLink', $definitions['link']->getName());

        $this->assertInstanceOf('Slick\Di\Definition\Helper\FactoryDefinitionHelper', $definitions['factory']);
        /** @var Definition\CallableDefinition $callable */
        $callable = $definitions['factory']->getDefinition('test');
        $this->assertInstanceOf('Slick\Di\Definition\CallableDefinition', $callable);

        $this->assertEquals('test', $callable->getName());
    }

    /**
     * Create a container object definition
     * @test
     */
    public function createObjectDefinition()
    {
        $definition = Definition::object('StdClass')
            ->constructor(['foo'])
            ->method('bar', ['bar'])
            ->scope(Definition\Scope::SINGLETON())
            ->property('baz', Definition::link('fooBar'));

        $this->assertInstanceOf('Slick\Di\Definition\Helper\ObjectDefinitionHelper', $definition);

        /** @var Definition\ObjectDefinition $objectDef */
        $objectDef = $definition->getDefinition('test');
        $this->assertInstanceOf('Slick\Di\Definition\ObjectDefinition', $objectDef);

        $this->assertEquals(Definition\Scope::SINGLETON(), $objectDef->getScope());
        $this->assertEquals('bar', $objectDef->getMethod('bar')->getMethodName());

        $this->assertEquals(['foo'], $objectDef->getConstructor()->getParameters());
        $this->assertInstanceOf(
            'Slick\Di\Definition\ObjectDefinition\EntryReference',
            $objectDef->getProperty('baz')->getValue()
        );
    }

}
