<?php

/**
 * Base
 * 
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Common;

use Serializable;

/**
 * Base
 * 
 * Base class uses the PHP magic methods to handle class properties in a
 * way that is a lot easier to work with. It defines an annotation for property
 * visibility and sets the "Getters" and "Setters" for all of this properties.
 * It prevents the creation of new properties as it throws exceptions if
 * you try to assign a value to an undefined property.
 * It also sets a very flexible constructor that allows you to create objects
 * only with some properties defined by passing an array (with those values)
 * or an object as argument.
 * Slick framework uses it in almost every class so it is important that
 * you understand how it works and the benefits of using it.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class Base implements Serializable
{

    /**
     * Trait with method for base class
     */
    use BaseMethods;
    
    /**
     * Constructor assign properties based on the array or object given.
     * 
     * The constructor will use the array keys or the object property
     * names to set the same property values with the ones given.
     * It will set a class inspector used for annotation read on properties.
     *
     * @param array|object $options The properties for the object
     *  being constructed.
     * 
     * @see \Slick\Common\Inspector
     */
    public function __construct($options = array())
    {
        $this->_createObject($options);
    }

    /**
     * Compares current object with provided one for equality
     * 
     * @param mixed|object $object The object to compare with
     * 
     * @return boolean True if the provided object is equal to this object
     */
    public function equals($object)
    {
        if (!is_object($object)) {
            return false;
        }

        if (!is_a($object, get_class($this))) {
            return false;
        }

        $props = array_keys(get_object_vars($this));
        $skip = array('_inspector', '___mocked');

        $equals = true;
        foreach ($props as $property) {
            if (in_array($property, $skip)) {
                continue;
            }
            $annotations = $this->_inspector->getPropertyAnnotations($property);
            $property = str_replace('_', '', $property);
            
            if (!$annotations->hasAnnotation('@write')
                && $this->$property != $object->$property
            ) {
                return false;
            }

        }
        return $equals;
    }

    /**
     * Sets necessary properties when object is unserialized.
     * Needed when using mock objects in tests.
     */
    public function __wakeup()
    {
        $this->_inspector = new Inspector($this);
    }

    /**
     * Removes unnecessary data for serializing.
     * @return string
     */
    public function serialize()
    {
        // @codingStandardsIgnoreStart
        unset($this->_inspector, $this->___mocked);
        // @codingStandardsIgnoreEnd
        $keys = array_keys(get_object_vars($this));
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->$key;
        }
        return serialize($data);
    }

    /**
     * Creates a new object from serialized data
     *
     * @param string $serialized
     *
     * @return Base
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->_inspector = new Inspector($this);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
}
