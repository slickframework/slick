<?php

/**
 * ObjectDefinition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

use Slick\Di\Definition\ObjectDefinition\PropertyInjection;
use Slick\Di\DefinitionInterface;
use Slick\Di\Definition\ObjectDefinition\MethodInjection;

/**
 * Object injection definition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectDefinition implements DefinitionInterface
{
    /**
     * Most of the time is the same as the class name
     * @var string
     */
    protected $_name;

    /**
     * In no class name is provided the class name is the name
     * @var string
     */
    protected $_className;

    /**
     * @var Scope
     */
    protected $_scope = Scope::SINGLETON;

    /**
     * Constructor definitions
     *
     * @var MethodInjection
     */
    protected $_constructor;

    /**
     * Methods definitions
     *
     * @var MethodInjection[]
     */
    protected $_methods = [];

    /**
     * Property definitions
     *
     * @var PropertyInjection[]
     */
    protected $_properties = [];

    /**
     * Creates an object definition
     *
     * @param string      $name
     * @param null|string $className
     */
    public function __construct($name, $className = null)
    {
        $this->_name = $name;
        $this->_className = $className;
    }

    /**
     * Returns the name of the entry in the container
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the scope of the entry
     *
     * @return Scope
     */
    public function getScope()
    {
        return $this->_scope;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->_className = $className;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        if (is_null($this->_className)) {
            return $this->_name;
        }
        return $this->_className;
    }

    /**
     * Sets the scope for the container
     *
     * @param Scope $scope
     *
     * @return ObjectDefinition
     */
    public function setScope(Scope $scope)
    {
        $this->_scope = $scope;
        return $this;
    }

    /**
     * Set constructor definition
     *
     * @param MethodInjection $constructor
     *
     * @return ObjectDefinition
     */
    public function setConstructor(MethodInjection $constructor)
    {
        $this->_constructor = $constructor;
        return $this;
    }

    /**
     * Returns constructor definition
     *
     * @return MethodInjection
     */
    public function getConstructor()
    {
        return $this->_constructor;
    }


    /**
     * Adds a method to this definition
     *
     * @param MethodInjection $method
     *
     * @return ObjectDefinition
     */
    public function addMethod(MethodInjection $method)
    {
        $this->_methods[$method->getMethodName()] = $method;
        return $this;
    }

    /**
     * Sets current methods list
     *
     * @param array $methods
     * @return ObjectDefinition
     */
    public function setMethods(array $methods)
    {
        $this->_methods = $methods;
        return $this;
    }

    /**
     * Returns current methods list
     *
     * @return ObjectDefinition\MethodInjection[]
     */
    public function getMethods()
    {
        return $this->_methods;
    }

    /**
     * Returns the method with provided name
     *
     * @param string $name
     *
     * @return null|MethodInjection
     */
    public function getMethod($name)
    {
        return (isset($this->_methods[$name])) ? $this->_methods[$name] : null;
    }


    /**
     * Adds a property definition to the property list for injection
     *
     * @param PropertyInjection $property
     * @return ObjectDefinition
     */
    public function addProperty(PropertyInjection $property)
    {
        $this->_properties[$property->getPropertyName()] = $property;
        return $this;
    }

    /**
     * Sets the current list of properties
     *
     * @param array $properties
     *
     * @return ObjectDefinition
     */
    public function setProperties(array $properties)
    {
        $this->_properties = $properties;
        return $this;
    }

    /**
     * Return the current list of properties
     *
     * @return ObjectDefinition\PropertyInjection[]
     */
    public function getProperties()
    {
        return $this->_properties;
    }

    /**
     * Returns the property with the given name
     *
     * @param string $name
     * @return null|PropertyInjection
     */
    public function getProperty($name)
    {
        return (isset($this->_properties[$name])) ?
            $this->_properties[$name] : null;
    }
}