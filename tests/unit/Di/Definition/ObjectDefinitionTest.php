<?php

/**
 * Object definition test case
 *
 * @package   Test\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di\Definition;

use Slick\Di\Container;
use Slick\Di\Definition\CallableDefinition;
use Slick\Di\Definition\DefinitionManager;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\ValueDefinition;
use Exception;

/**
 * Object definition test case
 *
 * @package   Test\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectDefinitionTest extends \Codeception\TestCase\Test
{

    /**
     * Create a simple object with definition and resolver
     * @test
     */
    public function createASimpleObject()
    {
        $definition = new ObjectDefinition('MyClass', 'Di\Definition\MyClass');
        $definition->setScope(Scope::PROTOTYPE());
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $obj = $container->get('MyClass');

        $this->assertInstanceOf('Di\Definition\MyClass', $obj);
    }

    /**
     * Creating an object with defined constructor
     * @test
     */
    public function createObjectWithDefinedConstructor()
    {

        $definition = new ObjectDefinition('MyClass', 'Di\Definition\MyClass');
        $definition->setScope(Scope::PROTOTYPE());
        $definition->setConstructor(new ObjectDefinition\MethodInjection('__construct', ['bar']));
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $obj = $container->get('MyClass');
        $this->assertEquals('bar', $obj->getName());

        $objA = $container->make('MyClass', ['name' => 'baz']);
        $this->assertEquals('baz', $objA->getName());
    }

    /**
     * Creating an object using object class name
     * @test
     */
    public function createObjectUsingClassName()
    {
        $definition = new ObjectDefinition('Di\Definition\MyClass');
        $definition->setScope(Scope::PROTOTYPE());
        $definition->setConstructor(new ObjectDefinition\MethodInjection('__construct', ['bar']));
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $obj = $container->get('Di\Definition\MyClass');
        $this->assertEquals('bar', $obj->getName());
    }

    /**
     * Inject constructor with entry reference
     * @test
     */
    public function injectConstructorReference()
    {
        $definition = new ObjectDefinition('MyClass');
        $definition->setClassName('Di\Definition\MyClass');
        $definitionValue = new ValueDefinition('MyName', 'Object test');
        $reference = new ObjectDefinition\EntryReference('MyName');
        $definition->setScope(Scope::PROTOTYPE());
        $definition->setConstructor(new ObjectDefinition\MethodInjection('__construct', [$reference]));
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition)->add($definitionValue);
        $container = new Container($definitionManager);

        $obj = $container->get('MyClass');
        $this->assertEquals('Object test', $obj->getName());
    }

    /**
     * Inject a constructor with mandatory parameter unset
     * @test
     * @expectedException \Slick\Di\Exception\DependencyException
     */
    public function injectInvalidArgument()
    {
        $definition = new ObjectDefinition('Di\Definition\OtherClass');
        $definition->setScope(Scope::PROTOTYPE());
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition);
        $container = new Container($definitionManager);

        $container->get('Di\Definition\OtherClass');
    }

    /**
     * Inject other methods from definition
     *
     * @test
     */
    public function injectOtherMethods()
    {
        $otherDefinition = new ObjectDefinition('OtherClass', 'Di\Definition\OtherClass');
        $otherDefinition->setScope(Scope::SINGLETON())
            ->setConstructor(new ObjectDefinition\MethodInjection('__construct', ['foo']));
        $definition = new ObjectDefinition('Di\Definition\MyClass');
        $definition->addMethod(new ObjectDefinition\MethodInjection('setGroup', ['admins']))
            ->addMethod(new ObjectDefinition\MethodInjection(
                'setOtherClass', [new ObjectDefinition\EntryReference('OtherClass')]
            ));
        $method = $definition->getMethod('setGroup');
        $this->assertInstanceOf(
            'Slick\Di\Definition\ObjectDefinition\MethodInjection',
            $method
        );
        $this->assertEquals(['admins'], $method->getParameters());
        $expected = [
            'setGroup' => new ObjectDefinition\MethodInjection('setGroup', ['admins']),
            'setOtherClass' => new ObjectDefinition\MethodInjection(
                    'setOtherClass', [new ObjectDefinition\EntryReference('OtherClass')])
        ];
        $this->assertEquals($expected, $definition->getMethods());

        $definition->setScope(Scope::PROTOTYPE());
        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition)->add($otherDefinition);

        $container = new Container($definitionManager);
        $objA = $container->get('Di\Definition\MyClass');
        $this->assertEquals('admins', $objA->getGroup());

        $this->assertInstanceOf('Di\Definition\OtherClass', $objA->getOtherClass());

    }

    /**
     * Add property injections
     * @test
     */
    public function injectProperties()
    {
        $class = 'Di\Definition\MyClass';
        $otherClass = new ObjectDefinition('OtherClass', 'Di\Definition\OtherClass');
        $otherClass->setConstructor(new ObjectDefinition\MethodInjection('__construct', ['Just foo']))
            ->setScope(Scope::PROTOTYPE());
        $definition = new ObjectDefinition($class);
        $definition->setConstructor(new ObjectDefinition\MethodInjection('__construct', ['My Foo']))
            ->addProperty(new ObjectDefinition\PropertyInjection(
                'otherClass', new ObjectDefinition\EntryReference('OtherClass')))
            ->addProperty(new ObjectDefinition\PropertyInjection('group', 'people'))
            ->setScope(Scope::SINGLETON());

        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition)->add($otherClass);
        $container = new Container($definitionManager);

        $objA = $container->get($class);

        $this->assertInstanceOf('Di\Definition\OtherClass', $objA->getOtherClass());
        $this->assertEquals('people', $objA->getGroup());

        $this->assertInstanceOf(
            'Slick\Di\Definition\ObjectDefinition\PropertyInjection',
            $definition->getProperty('group')
        );
        $this->assertEquals('group', $definition->getProperty('group')->getPropertyName());
        $this->assertEquals('people', $definition->getProperty('group')->getValue());
    }

    /**
     * Inject a property with dependency errors
     * @test
     * @expectedException \Slick\Di\Exception\DependencyException
     */
    public function injectPropertyWithError()
    {
        $class = 'Di\Definition\MyClass';
        $otherClass = new ObjectDefinition('errorClass', 'Di\Definition\OtherClass');
        $otherClass->addMethod(new ObjectDefinition\MethodInjection('none', []));
        $definition = new ObjectDefinition('withErrors', $class);
        $definition->setScope(Scope::PROTOTYPE());
        $otherClass->setScope(Scope::PROTOTYPE());
        $definition->addProperty(new ObjectDefinition\PropertyInjection(
            'otherClass', new ObjectDefinition\EntryReference('errorClass')));

        $definitionManager = new DefinitionManager();
        $definitionManager->add($definition)->add($otherClass);
        $container = new Container($definitionManager);

        $container->get('withErrors');
    }



}

/**
 * Mock class
 *
 * @package Di\Definition
 */
class MyClass
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_group = 'testers';

    /**
     * @var OtherClass
     */
    private $_otherClass;

    /**
     * Simple constructor
     *
     * @param string  $name
     * @param boolean $enable
     */
    public function __construct($name = 'foo', $enable = true)
    {
        $this->_name = $name;
    }

    /**
     * Return the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->_group = $group;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->_group;
    }

    /**
     * @param \Di\Definition\OtherClass $otherClass
     */
    public function setOtherClass(OtherClass $otherClass)
    {
        $this->_otherClass = $otherClass;
    }

    /**
     * @return \Di\Definition\OtherClass
     */
    public function getOtherClass()
    {
        return $this->_otherClass;
    }



}

/**
 * Mock class
 *
 * @package Di\Definition
 */
class OtherClass
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }
}
