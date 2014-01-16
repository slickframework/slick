<?php

/**
 * BaseMethods
 *
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common;

use Slick\Utility\Text;

/**
 * BaseMethods are common methods shared with Slick\Common\Base and
 * Slick\Common\BaseSingleton
 *
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait BaseMethods {

    /**
     * Handles the call for unimplemented methods.
     *
     * If called method is of type "getProperty", "setProperty" or "isProperty"
     * this method will check if the property exists and if the annotation
     * flags, like @read, @write or @readwrite are set to proper set or get the
     * property value.
     * If the property can't be read or written an exception will be thrown.
     * If the method isn't one of "getProperty", "setProperty" or "isProperty"
     * an exception will be thrown saying that the method isn't implemented.
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
	// @codingStandardsIgnoreStart
    public function __call($name, $arguments)
    {
    	// @codingStandardsIgnoreEnd
        if (is_null($this->_inspector)) {
            throw new Exception\BadConstructorException(
                "The constructor is not correct for use Slick\Common\Base"
                ." class. You need to call 'parent::__construct()' for the"
                ." right object initialization."
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
    // @codingStandardsIgnoreStart
    protected function _getter($name)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $tags = $this->_inspector->getPropertyMeta($property);

            if (!$tags->hasTag('@readwrite') && !$tags->hasTag('@read')) {
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
    // @codingStandardsIgnoreStart
    protected function _setter($name, $value)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $tags = $this->_inspector->getPropertyMeta($property);

            if (!$tags->hasTag('@readwrite') && !$tags->hasTag('@write')) {
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
    // @codingStandardsIgnoreStart
    protected function _is($name)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";

        if (property_exists($this, $property)) {
            $tags = $this->_inspector->getPropertyMeta($property);

            if (!$tags->hasTag('@readwrite') && !$tags->hasTag('@read')) {
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
    // @codingStandardsIgnoreStart
    public function __get($name)
    {
    	// @codingStandardsIgnoreEnd
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
    // @codingStandardsIgnoreStart
    public function __set($name, $value)
    {
    	// @codingStandardsIgnoreEnd
        $function = "set".ucfirst($name);
        return $this->$function($value);
    }
}