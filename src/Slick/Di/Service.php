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
    protected $_shared;

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
        return (boolean) $this->_shared;
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
        
    }
}