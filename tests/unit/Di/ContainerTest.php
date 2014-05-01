<?php

/**
 * Container use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di;

use Slick\Di\Container;
use Slick\Di\Definition\AliasDefinition;
use Slick\Di\Definition\CallableDefinition;
use Slick\Di\Definition\DefinitionManager;
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\ValueDefinition;
use Slick\Di\DefinitionInterface;
use Codeception\Util\Stub;
use Slick\Di\Resolver\ValueResolver;


/**
 * Container use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerTest extends \Codeception\TestCase\Test
{

    /**
     * Create a dependency container
     * @test
     */
    public function createDependencyContainer()
    {
        $definitionManager = new DefinitionManager();
        $container = new Container($definitionManager);

        $this->assertSame($container, $container->get('Slick\Di\Container'));
        $this->assertSame($container, $container->get('Slick\Di\ContainerInterface'));
    }

    /**
     * Check an entry existence
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function checkingEntryExistence()
    {
        $definitionManager = new DefinitionManager();
        $definitionManager->add(new ValueDefinition('foo', 'bar'));
        $container = new Container($definitionManager);

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('other'));

        $container->has(001);
    }

    /**
     * Check exception on invalid argument on get method
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function invalidCallToGet()
    {
        $definitionManager = new DefinitionManager();
        $container = new Container($definitionManager);

        $container->get(000);
    }

    /**
     * Resolve a definition
     * @test
     * @expectedException \Slick\Di\Exception\NotFoundException
     */
    public function resolveAnDefinition()
    {
        $definitionManager = new DefinitionManager();
        $definitionManager->add(new ValueDefinition('foo', 'bar'));
        $container = new Container($definitionManager);

        $this->assertEquals('bar', $container->get('foo'));

        $container->get('test');
    }

    /**
     * Resolving a definition without a resolver
     * @test
     * @expectedException \Slick\Di\Exception\NotFoundException
     */
    public function getUnknownResolver()
    {
        $definitionManager = new DefinitionManager();
        $definitionManager->add(new UnresolvedDefinition());
        $container = new Container($definitionManager);

        $container->get('test');
    }

    /**
     * Resolving a definition that throws an exception
     * @test
     * @expectedException \Slick\Di\Exception\DependencyException
     */
    public function resolveWithError()
    {
        $definition = Stub::construct('Slick\Di\Definition\ValueDefinition', ['baz', 'bar'], [
            'getValue' => function() {
                    throw new \Exception("Error occurred on this.");
                }
        ]);
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $container->addResolver(get_class($definition), new ValueResolver());

        $container->get('baz');

    }

    /**
     * Trying to add an invalid class resolver
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function addingInvalidResolver()
    {
        $definitionManager = new DefinitionManager();
        $definitionManager->add(new UnresolvedDefinition());
        $container = new Container($definitionManager);

        $container->addResolver('SomeClass', new ValueResolver());
    }

    /**
     * Using container as a factory
     * @test
     * @expectedException \Slick\Di\Exception\NotFoundException
     */
    public function useMakeFactory()
    {
        $callable = function($name) {
            $obj = new \StdClass();
            $obj->name = $name;
            return $obj;
        };

        $definition = new CallableDefinition('makeTest', $callable, ['foo']);
        $definition->setScope(Scope::SINGLETON());
        $this->assertNotEquals(Scope::PROTOTYPE(), $definition->getScope());
        $this->assertEquals(Scope::SINGLETON(), $definition->getScope());
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $objA = $container->get('makeTest');
        $this->assertEquals('foo', $objA->name);

        $objB = $container->make('makeTest', ['foo']);
        $this->assertEquals('foo', $objB->name);

        $this->assertNotSame($objA, $objB);
        $this->assertEquals($objA, $objB);

        $container->make('anotherTest');
    }

    /**
     * Trying to call make with invalid name
     * @test
     * @expectedException \Slick\Di\Exception\InvalidArgumentException
     */
    public function callMakeWithInvalidName()
    {
        $definitionManager = new DefinitionManager();
        $definitionManager->add(new UnresolvedDefinition());
        $container = new Container($definitionManager);

        $container->make(0010);
    }

    /**
     * Trying to get an alias entry
     * @test
     */
    public function getAnAliasEntry()
    {
        $callable = function($name) {
            $obj = new \StdClass();
            $obj->name = $name;
            return $obj;
        };

        $definition = new CallableDefinition('makeTest', $callable, ['foo']);
        $alias = new AliasDefinition('alias', 'makeTest');

        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition)->add($alias);
        $container = new Container($definitionManager);

        $objA = $container->get('makeTest');
        $objB = $container->get('alias');

        $this->assertSame($objA, $objB);
    }
}

class UnresolvedDefinition implements DefinitionInterface
{

    /**
     * Returns the name of the entry in the container
     *
     * @return string
     */
    public function getName()
    {
        return 'test';
    }

    /**
     * Returns the scope of the entry
     *
     * @return Scope
     */
    public function getScope()
    {
        Scope::PROTOTYPE();
    }
}
