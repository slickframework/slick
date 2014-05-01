<?php

/**
 * ObjectDefinitionHelper
 *
 * @package   Slick\Di\Definition\Helper
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition\Helper;

use Slick\Di\Definition\DefinitionHelperInterface;
use Slick\Di\Definition\ObjectDefinition\MethodInjection;
use Slick\Di\Definition\ObjectDefinition\PropertyInjection;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Di\Definition\Scope;

/**
 * Helps construct an object definition
 *
 * @package   Slick\Di\Definition\Helper
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectDefinitionHelper implements DefinitionHelperInterface
{
    /**
     * @var MethodInjection
     */
    protected $_constructor;

    /**
     * @var MethodInjection[]
     */
    protected $_methods = [];

    /**
     * @var PropertyInjection[}
     */
    protected $_properties = [];

    /**
     * @var string
     */
    protected $_className;

    /**
     * @var Scope
     */
    protected $_scope;

    /**
     * Sets the class name if needed
     *
     * @param string $className
     */
    public function __construct($className = null)
    {
        $this->_className = $className;
        $this->_scope = Scope::PROTOTYPE();
    }


    /**
     * Returns an object definition
     *
     * @param string $entryName Container entry name
     *
     * @return \Slick\Di\DefinitionInterface
     */
    public function getDefinition($entryName)
    {
        $definition = new ObjectDefinition($entryName, $this->_className);
        $definition->setConstructor($this->_constructor)
            ->setMethods($this->_methods)
            ->setProperties($this->_properties)
            ->setScope($this->_scope);

        return $definition;
    }

    /**
     * Defines the constructor parameters
     *
     * @param array $params
     *
     * @return ObjectDefinitionHelper
     */
    public function constructor(array $params)
    {
        $this->_constructor = new MethodInjection('__construct', $params);
        return $this;
    }

    /**
     * Defines a method injection
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return ObjectDefinitionHelper
     */
    public function method($name, array $parameters = [])
    {
        $this->_methods[$name] = new MethodInjection($name, $parameters);
        return $this;
    }

    /**
     * Defines a property injection
     *
     * @param string $name
     * @param mixed $value
     *
     * @return ObjectDefinitionHelper
     */
    public function property($name, $value)
    {
        $this->_properties[$name] = new PropertyInjection($name, $value);
        return $this;
    }

    /**
     * Defines object instantiation scope
     *
     * @param Scope $scope
     * @return ObjectDefinitionHelper
     */
    public function scope(Scope $scope)
    {
        $this->_scope = $scope;
        return $this;
    }
}