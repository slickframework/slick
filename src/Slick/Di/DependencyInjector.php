<?php

/**
 * DependencyInjector
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

/**
 * DependencyInjector
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DependencyInjector extends AbstractDependencyInjector
    implements DiInterface
{

    /**
     * @readwrite
     * @var array List of services in this container
     */
    protected $_services = array();

    /**
     * @read
     * @var array List of service instances
     */
    protected $_sharedInstances = array();

    /**
     * @read
     * @var boolean
     */
    protected $_freshInstance = false;

    /**
     * Registers a service in the services container
     * 
     * @param string  $name       The service name
     * @param mixed   $definition The service definition
     * @param boolean $shared     A flag to set this service as a shared service
     *
     * @return ServiceInterface
     */
    public function set($name, $definition, $shared = false)
    {
        $srv = new Service(
            array(
                'name' => $name,
                'definition' => $definition,
                'shared' => $shared
            )
        );
        $this->_services[$name] = $srv;
        return $srv;
    }

    /**
     * Registers a shared service in the services container
     * 
     * @param string  $name       The service name
     * @param mixed   $definition The service definition
     *
     * @return ServiceInterface
     */
    public function setShared($name, $definition)
    {
        return $this->set($name, $definition, true);
    }

    /**
     * Removes a service in the services container
     * 
     * @param string $name The service name
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->_services[$name]);
        }
    }

    /**
     * Attempts to register a service in the servives container
     *
     * This method is similar to the DiInterface::set() except that it will
     * only register the service if a service hasn’t been registered previously
     * with the same name
     * 
     * @param string  $name       The service name
     * @param mixed   $definition The service definition
     * @param boolean $shared     A flag to set this service as a shared service
     *
     * @return ServiceInterface
     */
    public function attempt($name, $definition, $shared = false)
    {
        if (!$this->has($name)) {
            return $this->set($name, $definition, $shared);
        }
        return $this->getService($name);
    }

    /**
     * Returns a service based on their configuration
     * 
     * @param string $name       The service name
     * @param array  $parameters Parameters to set on resolved service instance
     * 
     * @return object The service instance
     */
    public function get($name, $parameters = array())
    {
        $instance = $this->getService($name)->resolve($parameters, $this);
        if (!isset($this->_sharedInstances[$name])) {
            $this->_freshInstance = true;
            $this->_sharedInstances[$name] = $instance;
        } else {
            $this->_freshInstance = false;
        }
        return $instance;
    }

    /**
     * Returns a shared service based on their configuration
     * 
     * @param string $name       The service name
     * @param array  $parameters Parameters to set on resolved service instance
     * 
     * @return object The service instance
     */
    public function getShared($name, $parameters = array())
    {
        if (!isset($this->_sharedInstances[$name])) {
            return $this->get($name, $parameters);
        }
        return $this->_sharedInstances[$name];
    }

    /**
     * Returns a service definition without resolving
     * 
     * @param string $name The service name
     * 
     * @return ServiceInterface
     */
    public function getService($name)
    {
        if ($this->has($name))
            return $this->_services[$name];

        throw new Exception\ServiceNotFoundException(
            "The service '{$name}' was not found on this dependency " .
            "injector container."
        );
        
    }

    /**
     * Check whether the DI contains a service by a name
     * 
     * @param string $name The service name
     * 
     * @return boolean True if service exists, false otherwise.
     */
    public function has($name)
    {
        return isset($this->_services[$name]);
    }

    /**
     * Check whether the last service obtained via getShared produced a fresh
     * instance or an existing one
     * 
     * @return boolean True if it was a fresh instance, false otherwise.
     */
    public function wasFreshInstance()
    {
        return $this->_freshInstance;
    }
}