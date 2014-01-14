<?php

/**
 * AbstractDependencyInjector
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
 * AbstractDependencyInjector
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDependencyInjector extends Base
    implements DiInterface
{

    /**
     * @readwrite
     * @var array List of services in this container
     */
    protected $_services = array();

    /**
     * @readwrite
     * @var boolean Flag for instance freshness
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
     * Check whether the DI contains a service by a name
     * 
     * @param mixed $offset The service name
     * 
     * @return boolean True if service exists, false otherwise.
     */
    public function offsetExists ($offset)
    {
        return $this->has($offset);
    }

    /**
     * Returns a service based on their configuration
     * 
     * @param mixed $offset The service name
     * 
     * @return object The service instance
     */
    public function offsetGet ($offset)
    {
        return $this->get($offset);
    }

    /**
     * Registers a service in the services container
     * 
     * @param string $offset The service name
     * @param mixed  $value  The service definition
     */
    public function offsetSet ($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * Removes a service in the services container
     * 
     * @param string $offset The service name
     */
    public function offsetUnset ($offset)
    {
        return $this->remove($offset);
    }
}