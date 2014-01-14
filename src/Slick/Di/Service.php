<?php

/**
 * Service
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Common\Base;

/**
 * Service
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Service extends Base implements ServiceInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_shared = false;

    /**
     * @readwrite
     * @var mixed
     */
    protected $_definition;

    /**
     * @readwrite
     * @var string
     */
    protected $_className = '\StdClass';

    /**
     * @readwrite
     * @var array
     */
    protected $_arguments = array();

    /**
     * @readwrite
     * @var array
     */
    protected $_calls = array();

    /**
     * @readwrite
     * @var array
     */
    protected $_properties = array();

    /**
     * @readwrite
     * @var Object
     */
    protected $_instance = null;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_callable = false;

    /**
     * @readwrite
     * @var boolean
     */
    protected $_closure = false;

    /**
     * Returns the service’s name
     * 
     * @return string Service name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the service as a shared service or not
     * 
     * @param boolean $shared True if the service is shared or false otherwise
     * 
     * @return Service A self instance for method chain calls
     */
    public function setShared($shared)
    {
        $this->_shared = $shared;
        return $this;
    }

    /**
     * Check whether the service is shared or not
     * 
     * @return boolean True if service is shared, false otherwise
     */
    public function isShared()
    {
        return $this->_shared;
    }

    /**
     * Set the service definition
     * 
     * @param mixed $definition The service definition
     *
     * @return Service A self instance for method chain calls
     */
    public function setDefinition($definition)
    {
        $this->_definition = $definition;
        return $this;
    }

    /**
     * Returns the service definition
     * 
     * @return mixed The service definition
     */
    public function getDefinition()
    {
        return $this->_definition;
    }

    /**
     * Resolves the service
     * 
     * @param array       $options            Service initialization options
     * @param DiInterface $dependencyInjector A dependency
     * 
     * @return object The service instance
     */
    public function resolve(
        $options = array(), DiInterface $dependencyInjector = null)
    {

        if ($this->isShared() && is_object($this->_instance)) {
            return $this->_instance;
        }

        Service\DefinitionParser::parse($this->_definition, $this);

        if ($this->isCallable()) {
            return call_user_func($this->_definition);
        }

        if ($this->isClosure()) {
            $closure = $this->definition;
            return $closure();
        }

        if (is_object($this->_definition)) {
            $this->_instance = $this->_definition;
            return $this->_instance;
        }

        return $this->_createObject($options, $dependencyInjector);
    }

    /**
     * Creates service instance based on parsed definition
     * @return object The service instance
     */
    protected function _createObject(
        $options = array(), DiInterface $dependencyInjector = null)
    {

        $reflection = new \ReflectionClass($this->getClassName()); 
        $instance = $reflection->newInstanceArgs(
            $this->prepareArguments($this->getArguments(), $dependencyInjector)
        );

        if (is_a($instance, 'Slick\Di\DiAwareInterface')) {
            $instance->setDi($dependencyInjector);
        }

        foreach ($this->_properties as $property => $value) {
            $instance->$property = $value;
        }

        foreach ($this->_calls as $call) {
            $method = $call['method'];
            $args = isset($call['arguments']) ?
                $this->prepareArguments(
                    $call['arguments'],
                    $dependencyInjector
                ) : array();
            call_user_func_array(
                array($instance, $call['method']),
                $args
            );
        }
        $this->_instance = $instance;
        return $instance;
    }

    /**
     * Parses the definition argumen array 
     * 
     * @param array       $args              
     * @param DiInterface $dependencyInjector
     * 
     * @return array A list of parsed arguments to use.
     */
    public function prepareArguments(
        $args, DiInterface $dependencyInjector = null)
    {
        $result = array();
        foreach ($args as $arg) {
            $result[] = $this->_resolveArgument($arg, $dependencyInjector);
        }
        return $result;
    }

    /**
     * Resolves the argument data.
     * 
     * @param array       $data
     * @param DiInterface $dependencyInjector
     * 
     * @return mixed The resolved value for the argumen
     */
    public function _resolveArgument(
        $data, DiInterface $dependencyInjector = null)
    {
        $value = $data;
        switch ($data['type']) {
            case 'service':
                $value = $dependencyInjector->get($data['name']);
                break;

            case 'parameter':
                $value = $data['value'];
                break;       
            
            case 'instance':
                $reflection = new \ReflectionClass($data['className']); 
                $value = $reflection->newInstanceArgs(
                    $this->prepareArguments($data['arguments'])
                );
                break;
        }
        return $value;
    }
}