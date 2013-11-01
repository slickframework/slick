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

use Slick\Utility\Text,
    Slick\Common\Inspector,
    Slick\Common\Exception;

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
 * you understand how it works and the beneficts of using it.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Base
{
    
    /**
     * @var \Slick\common\Inspector The self inspector object.
     */
    private $_inspector = null;

    /**
     * @readwrite
     * @var mixed Used by codeception in test mockups.
     */
    // @codingStandardsIgnoreStart
    public $___mocked;
    // @codingStandardsIgnoreEnd
    
    /**
     * Constructor assign ptoperties based on the array or object given.
     * 
     * The constructor will use the array keys or the object property
     * names to set the same property values with the ones given.
     * It will set a class inspector used for annotaion read on properties.
     *
     * @param array|object $options The properties for the object
     *  beeing constructed.
     * 
     * @see \Slick\Common\Inspector
     */
    public function __construct($options = array())
    {
        $this->_inspector = new Inspector($this);
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $value) {
                $key = ucfirst($key);
                $method = "set{$key}";
                $this->$method($value);
            }
        }
    }
    
    /**
     * Sets necessary properties when unserializing.
     */
    public function __wakeup()
    {
        $this->_inspector = new Inspector($this);
    }
    
    /**
     * Removes unecessary data for serializing.
     */
    public function __sleep()
    {
        // @codingStandardsIgnoreStart
        unset($this->_inspector, $this->___mocked);
        // @codingStandardsIgnoreEnd
        return array_keys(get_object_vars($this));
    }

        /**
     * Handles the call for unimplemented methods.
     *
     * If called method is of type "getProperty", "setProperty" or "isProperty"
     * this method will check if the property exists and if the annotation
     * flags, like @read, @write or @readwrite are set to proper set or get the
     * property value.
     * If the property can't be read or written an execption will be trown.
     * If the method isn't one of "getProperty", "setProperty" or "isProperty"
     * an exception will be trown saying that the method isn't implemented.
     *
     * @param string $name      The calling method.
     * @param array  $arguments An array with the arguments passed to the
     *  method calling.
     * 
     * @return mixed Will return the property value or the current
     *  instance for chain calls if the calling method was of type setProperty.
     * 
     * @throws \Slick\Common\Exception\BadConstructorException
     * @throws \Slick\Common\Exception\UnimplementedMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (is_null($this->_inspector)) {
            throw new Exception\BadConstructorException(
                "The constructor isn\'t correct for use Slick\Common\Base"
                ." class. You need to call 'parent::__construct()' for the"
                ." right object initializantion."
            );
        }

        $method = null;

        //check if the call is a getter
        $getMatches = Text::match($name, "^get([a-zA-Z0-9\_]+)$");
        if (sizeof($getMatches) > 0) {
            $method = 'getter';
        }

        //check if the call is a setter
        $setMatches = Text::match($name, '^set([a-zA-Z0-9\_]+)$');
        if (sizeof($setMatches) > 0) {
            $method = 'setter';
        }
        
        //check if the call is an is (boolean check)
        $isMatches = Text::match($name, '^is([a-zA-Z0-9\_]+)$');
        if (sizeof($isMatches) > 0) {
            $method = 'is';
        }

        switch ($method) {
            case 'getter':
                return $this->_getter($getMatches[0]);

            case 'setter':
                return $this->_setter($setMatches[0], $arguments[0]);
            
            case 'is':
                return $this->_is($isMatches[0]);
        }
        
        $className = get_class($this);
        throw new Exception\UnimplementedMethodCallException(
            "The method '{$className}::{$name}()' its not defined."
        );
    }
    
    /**
     * Retrieves the value a property with the given name.
     * 
     * @param string $name The property name to get the value.
     * 
     * @return mixed The property value.
     * 
     *  @throws \Slick\Common\Exception\WriteOnlyException
     */
    protected function _getter($name)
    {
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $meta = $this->_inspector->getPropertyMeta($property);

            if (empty($meta['@readwrite']) && empty($meta['@read'])) {
                $className = get_class($this);
                throw new Exception\WriteOnlyException(
                    "Trying to read the values of a write only property."
                    ." {$className}::\${$property} has annotation @write."
                );
            }
            return $this->$property;
        }
        return null;
    }

    /**
     * Sets the value of a given property name.
     * 
     * @param string $name  The property name to set the value.
     * @param mixed  $value The value to assign to property.
     * 
     * @return \Slick\Common\Base The current object instance for
     *  multiple (chain) method calls.
     * 
     * @throws \Slick\Common\Exception\ReadOnlyException
     * @throws \Slick\Common\Exception\UndefinedPropertyException
     */
    protected function _setter($name, $value)
    {
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $meta = $this->_inspector->getPropertyMeta($property);

            if (empty($meta['@readwrite']) && empty($meta['@write'])) {
                $className = get_class($this);
                throw new Exception\ReadOnlyException(
                    "Trying to assign a value to a read only property."
                    ." {$className}::\${$property} has annotation @read."
                );
            }
            
            $this->$property = $value;
            return $this;
        }

        $className = get_class($this);
        throw new Exception\UndefinedPropertyException(
            "Trying to assign a value to an undefined property."
            . " {$className}::\${$property} doesn't exists."
        );

    }
    
    /**
     * Retrieves the boolean value a property with the given name.
     * 
     * @param type $name The property name to get the value.
     * 
     * @return boolean The boolean value of the requested property.
     * 
     * @throws \Slick\Common\Exception\WriteOnlyException
     */
    protected function _is($name)
    {
        $normalized = lcfirst($name);
        $property = "_{$normalized}";

        if (property_exists($this, $property)) {
            $meta = $this->_inspector->getPropertyMeta($property);
            
            if (empty($meta['@readwrite']) && empty($meta['@read'])) {
                $className = get_class($this);
                throw new Exception\WriteOnlyException(
                    "Trying to read the values of a write only property."
                    ." {$className}::\${$property} has annotation @write."
                );
            }
            
            return (boolean) $this->$property;
        }
        return false;
    }

    /**
     * Handles the call for unimplemented or invisible properties.
     *
     * This will result in a call to "getProperty" method handled with the
     * magic method \Slick\Common\Base::__call().
     *
     * @param string $name The requested property name.
     * 
     * @return mixed The property value or null, if property isn't set.
     * 
     * @see \Slick\Common\Base::__call()
     */
    public function __get($name)
    {
        $function = "get".ucfirst($name);
        return $this->$function();
    }

    /**
     * Handles the call to assign values to invisible/unimplemented properties.
     *
     * This will result in a call to setName method handled with the
     * magic method \Slick\Common\Base::__call().
     *
     * @param string $name  The requested property name.
     * @param mixed  $value The value to assign to the property.
     * 
     * @return \Slick\Common\Base The current object instance for
     *  multiple (chain) method calls.
     * 
     * @see \Slick\Common\Base::__call()
     */
    public function __set($name, $value)
    {
        $function = "set".ucfirst($name);
        return $this->$function($value);
    }
}
