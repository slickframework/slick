<?php

/**
 * CallableDefinition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Definition to use with callable
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CallableDefinition implements DefinitionInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var Scope
     */
    protected $_scope;

    /**
     * @var callable
     */
    protected $_callable;

    /**
     * @var array
     */
    protected $_args;

    /**
     * Creates a new callable definition
     *
     * @param string   $name
     * @param callable $callable
     * @param array    $args
     */
    public function __construct($name, callable $callable, array $args = [])
    {
        $this->_name = $name;
        $this->_callable = $callable;
        $this->_args = $args;
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
        return (is_null($this->_scope)) ?: Scope::SINGLETON();
    }

    /**
     * Sets definition scope
     *
     * @param Scope $scope
     *
     * @return CallableDefinition
     */
    public function setScope(Scope $scope)
    {
        $this->_scope = $scope;
        return $this;
    }

    /**
     * Returns the callable
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->_callable;
    }

    /**
     * Returns the arguments to use with callable
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->_args;
    }


}