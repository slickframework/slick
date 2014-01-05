<?php

/**
 * DiInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use ArrayAccess;

/**
 * DiInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DiInterface extends ArrayAccess
{

	/**
	 * Registers a service in the services container
	 * 
	 * @param string  $name       The service name
	 * @param mixed   $definition The service definition
	 * @param boolean $shared     A flag to set this service as a shared service
	 *
	 * @return ServiceInterface
	 */
	public function set($name, $definition, $shared = false);

	/**
	 * Registers a shared service in the services container
	 * 
	 * @param string  $name       The service name
	 * @param mixed   $definition The service definition
	 *
	 * @return ServiceInterface
	 */
	public function setShared($name, $definition);

	/**
	 * Removes a service in the services container
	 * 
	 * @param string $name The service name
	 */
	public function remove($name);

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
	public function attempt($name, $definition, $shared = false);

	public function getShared($name, $parameters = array());

	public function getService($name);


}