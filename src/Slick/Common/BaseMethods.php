<?php

/**
 * BaseMethods
 *
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common;

use Slick\Utility\Text;
use Slick\Common\Inspector\AnnotationsList;

/**
 * BaseMethods are common methods shared with Slick\Common\Base and
 * Slick\Common\BaseSingleton
 *
 * @package   Slick\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait BaseMethods
{
    /**
     * @var \Slick\common\Inspector The self inspector object.
     */
    private $inspector = null;

    // @codingStandardsIgnoreStart
    /**
     * @readwrite
     * @var mixed Used by codeception in test mockups.
     * @internal
     */
    public $___mocked;
    // @codingStandardsIgnoreEnd

    protected function _createObject($options = [])
    {
        $this->inspector = new Inspector($this);
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $value) {
                $key = ucfirst($key);
                $method = "set{$key}";
                $this->$method($value);
            }
        }
    }

    // @codingStandardsIgnoreStart
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
     * @param string $name The calling method.
     * @param array  $arguments An array with the arguments passed to the
     * method calling.
     * 
     * @return mixed Will return the property value or the current instance
     * for chain calls if the calling method was of type setProperty.
     * 
     * @throws Exception\BadConstructorException If the client class does runs
     * this controller that will initialize the inspector needed for the
     * magic methods implementation.
     * @throws Exception\UnimplementedMethodCallException If the method called
     * is not defined
     */
    public function __call($name, $arguments)
    {
    	// @codingStandardsIgnoreEnd
        if (is_null($this->inspector)) {
            throw new Exception\BadConstructorException(
                "The constructor is not correct for use Slick\Common\Base"
                ." class. You need to call 'parent::__construct()' for the"
                ." right object initialization. "
                ." Using object ".get_class($this)
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

    // @codingStandardsIgnoreStart
    /**
     * Retrieves the value a property with the given name.
     * 
     * @param string $name The property name to get the value.
     * 
     * @return mixed The property value.
     * 
     * @throws Exception\WriteOnlyException If the property being accessed
     * has the annotation @write
     */
    protected function _getter($name)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            /** @var AnnotationsList $annotations */
            $annotations = $this->inspector->getPropertyAnnotations($property);

            if (
                !$annotations->hasAnnotation('@readwrite') &&
                !$annotations->hasAnnotation('@read')
            ) {
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

    // @codingStandardsIgnoreStart
    /**
     * Sets the value of a given property name.
     * 
     * @param string $name The property name to set the value.
     * @param mixed  $value The value to assign to property.
     * 
     * @return self The current object instance for
     * multiple (chain) method calls.
     * 
     * @throws Exception\ReadOnlyException If the property being changed
     * has the annotation @read
     * @throws Exception\UndefinedPropertyException If the property does
     * not exists in class scope
     */
    protected function _setter($name, $value)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $annotations = $this->inspector->getPropertyAnnotations($property);
            /** @var AnnotationsList $annotations */
            if (
                !$annotations->hasAnnotation('@readwrite') &&
                !$annotations->hasAnnotation('@write')
            ) {
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

    // @codingStandardsIgnoreStart
    /**
     * Retrieves the boolean value a property with the given name.
     * 
     * @param string $name The property name to get the value.
     * 
     * @return boolean The boolean value of the requested property.
     * 
     * @throws Exception\WriteOnlyException If property being accessed has
     * the annotation @write
     */
    protected function _is($name)
    {
    	// @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";

        if (property_exists($this, $property)) {
            /** @var AnnotationsList $annotations */
            $annotations = $this->inspector->getPropertyAnnotations($property);

            if (
                !$annotations->hasAnnotation('@readwrite') &&
                !$annotations->hasAnnotation('@read')
            ) {
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

    // @codingStandardsIgnoreStart
    /**
     * Handles the call for unimplemented or invisible properties.
     *
     * This will result in a call to "getProperty" method handled with the
     * magic {@see \Slick\Common\Base::__call()} method.
     *
     * @param string $name The requested property name.
     * 
     * @return mixed The property value or null, if property isn't set.
     */
    public function __get($name)
    {
    	// @codingStandardsIgnoreEnd
        $function = "get".ucfirst($name);
        return $this->$function();
    }

    // @codingStandardsIgnoreStart
    /**
     * Handles the call to assign values to invisible/unimplemented properties.
     *
     * This will result in a call to setName method handled with the
     * magic method \Slick\Common\Base::__call().
     *
     * @param string $name The requested property name.
     * @param mixed  $value The value to assign to the property.
     * 
     * @return self The current object instance for multiple
     * (chain) method calls.
     * 
     * @see \Slick\Common\Base::__call()
     * @internal
     */
    public function __set($name, $value)
    {
    	// @codingStandardsIgnoreEnd
        $function = "set".ucfirst($name);
        return $this->$function($value);
    }

    /**
     * Checks if a given property name exists and can be accessed
     *
     * @param string $name The property name
     *
     * @return bool True if a property with the provided name exists,
     * false otherwise
     */
    public function __isset($name)
    {
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            return true;
        }
        return false;
    }
}
