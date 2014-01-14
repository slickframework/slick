<?php

/**
 * ServiceInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

/**
 * ServiceInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ServiceInterface
{

    /**
     * Returns the service’s name
     * 
     * @return string Service name
     */
    public function getName();

    /**
     * Sets the service as a shared service or not
     * 
     * @param boolean $shared True if the service is shared or false otherwise
     * 
     * @return ServiceInterface A self instance for method chain calls
     */
    public function setShared($shared);

    /**
     * Check whether the service is shared or not
     * 
     * @return boolean True if service is shared, false otherwise
     */
    public function isShared();

    /**
     * Set the service definition
     * 
     * @param mixed $definition The service definition
     *
     * @return ServiceInterface A self instance for method chain calls
     */
    public function setDefinition($definition);

    /**
     * Returns the service definition
     * 
     * @return mixed The service definition
     */
    public function getDefinition();

    /**
     * Resolves the service
     * 
     * @param array       $options            Service initialization options
     * @param DiInterface $dependencyInjector A dependency
     * 
     * @return object The service instance
     */
    public function resolve(
        $options = array(), DiInterface $dependencyInjector = null);
}