<?php

/**
 * Method Injection
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition\ObjectDefinition;

/**
 * Method definition to use in constructor injection and method injection
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MethodInjection
{

    /**
     * @var string
     */
    protected $_methodName;

    /**
     * @var array
     */
    protected $_parameters;

    /**
     * Creates a method definition to use in constructor or method injection
     *
     * @param string $methodName
     * @param array  $parameters
     */
    public function __construct($methodName, array $parameters = [])
    {
        $this->_methodName = $methodName;
        $this->replaceParameters($parameters);
    }

    /**
     * Returns the method name
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->_methodName;
    }

    /**
     * Returns method parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Replace the parameters of the definition by a new array of parameters.
     *
     * @param array $parameters
     *
     * @return MethodInjection
     */
    public function replaceParameters(array $parameters)
    {
        $this->_parameters = $parameters;
        return $this;
    }

    /**
     * Checks if a value exists at the provided position index
     *
     * @param int $index
     * @return bool
     */
    public function hasParameter($index)
    {
        return isset($this->_parameters[$index]);
    }

    /**
     * Returns the value at the provided position index
     *
     * @param int $index
     * @return null|mixed
     */
    public function getParameter($index) {
        $parameter = null;
        if ($this->hasParameter($index)) {
            $parameter = $this->_parameters[$index];
        }
        return $parameter;
    }
} 