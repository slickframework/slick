<?php

/**
 * PropertyList
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

/**
 * PropertyList is a hash map for lists of properties.
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class PropertyList extends \ArrayIterator
{

    /**
     * Adds a property to the list of properties
     * 
     * @param string $name  The property name
     * @param string $value The property value
     * 
     * @return Slick\Utility\PropertyList Self instance for method call chains
     */
    public function appen($name, $value = true)
    {
        $this[$name] = $value;
    }

    /**
     * Adds a property to the list of properties
     * 
     * @param string $name  The property name
     * @param string $value The property value
     * 
     * @return Slick\Utility\PropertyList Self instance for method call chains
     */
    public function setPropery($name, $value = true)
    {
        return $this->appen($name, $value);
    }

    /**
     * Checks if a property with a given name exists in the list
     * 
     * @param string $name  The property name
     * 
     * @return boolean True if property exists.
     */
    public function hasProperty($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Returns the value of the property with the provided name
     * 
     * @param string $name  The property name
     * 
     * @return mixed The property value
     */
    public function getProperty($name)
    {
        return $this[$name];
    }
}