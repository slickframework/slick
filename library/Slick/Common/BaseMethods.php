<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

use Slick\Common\Utils\Text;

/**
 *  Base class methods implementing getters and setters using PHP magic methods
 * __get() __set() __is() and __call().
 *
 * This trait uses the PHP magic methods to handle class properties in a
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
 * @package Slick\Common
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
trait BaseMethods
{

    /**
     * @var array List of method names anf their regexp
     */
    private static $patterns = [
        'getter' => '^get([a-zA-Z0-9\_]+)$',
        'setter' => '^set([a-zA-Z0-9\_]+)$',
        'is' => '^is([a-zA-Z0-9\_]+)$'
    ];

    /**
     * Sets current object with data from provided array or object
     *
     * @param array|object $data An associative array or object where to
     *                           extract the property values from.
     *
     * @return self|BaseMethods The object instance it self for method
     *                          call chaining.
     */
    protected function hydrate($data)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                $key = ucfirst($key);
                $method = "set{$key}";
                $this->$method($value);
            }
        }
        return $this;
    }

    /**
     * Handles the call for unimplemented methods.
     *
     * If called method is one of "getProperty", "setProperty" or "isProperty"
     * this method will check if the property exists and if the annotation
     * flags, like @read, @write or @readwrite are set to proper assign or
     * retrieve the property value.
     * If the property can't be read or written an exception will be thrown.
     * If the method isn't one of "getProperty", "setProperty" or "isProperty"
     * an exception will be thrown saying that the method isn't implemented.
     *
     * @param string $name      The calling method.
     * @param array  $arguments An array with the arguments passed to the
     *                          method calling.
     *
     * @return mixed Will return the property value or the current instance
     * for chain calls if the calling method was of type setProperty.
     *
     * @throws Exception\UnimplementedMethodCallException If the method called
     * is not defined
     */
    public function __call($name, $arguments)
    {

        list ($method, $matches) = $this->getMethod($name);

        if (is_null($method)) {
            $className = get_class($this);
            throw new Exception\UnimplementedMethodCallException(
                "The method '{$className}::{$name}()' its not defined."
            );
        }

        $value = isset($arguments[0])
            ? $arguments[0]
            : null;

        return $this->$method($matches, $value);
    }

    /**
     * Handles the call for unimplemented or invisible properties.
     *
     * This will result in a call to "getProperty" method handled with the
     * magic {@see \Slick\Common\BaseMethods::__call()} method.
     *
     * @param string $name The requested property name.
     *
     * @return mixed The property value or null, if property isn't set.
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
     * magic method \Slick\Common\BaseMethods::__call().
     *
     * @param string $name The requested property name.
     * @param mixed  $value The value to assign to the property.
     *
     * @return self The current object instance for multiple
     * (chain) method calls.
     *
     * @see \Slick\Common\BaseMethods::__call()
     * @internal
     */
    public function __set($name, $value)
    {
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
        return false !== $this->getProperty($name);
    }

    /**
     * Retrieves the value of a property with the given name.
     *
     * @param string $name The property name where to get the value from.
     *
     * @return mixed The property value.
     *
     * @throws Exception\WriteOnlyException If the property being accessed
     * has the annotation @write
     */
    protected function getter($name)
    {
        $property = $this->getProperty($name);
        if (false !== $property) {
            $annotations = $this->getInspector()
                ->getPropertyAnnotations($property);
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

    /**
     * Sets the value of the property with the given name.
     *
     * @param string $name  The property name to set the value.
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
    protected function setter($name, $value)
    {
        $property = $this->getProperty($name);
        if (false !== $property) {
            $annotations = $this->getInspector()
                ->getPropertyAnnotations($property);
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

    /**
     * Retrieves the boolean value of the property with the provided name.
     *
     * @param string $name The property name to get the value.
     *
     * @return boolean The boolean value of the requested property.
     *
     * @throws Exception\WriteOnlyException If property being accessed has
     * the annotation @write
     */
    protected function is($name)
    {
        $property = $this->getProperty($name);

        if (false !== $property) {
            $annotations = $this->getInspector()->
            getPropertyAnnotations($property);
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

    /**
     * Parses the provided name to verify what method should be called
     *
     * @param string $name
     *
     * @return array
     */
    private function getMethod($name)
    {
        $method = null;
        $matches = null;
        foreach (self::$patterns as $methodName => $regexp)
        {
            $matches = Text::match($name, $regexp);
            if (sizeof($matches) > 0) {
                $method = $methodName;
                $matches = $matches[0];
                break;
            }
        }

        return [$method, $matches];
    }

    /**
     * Retrieve the current class metadata
     *
     * @return Inspector
     */
    private function getInspector()
    {
        return Inspector::forClass($this);
    }

    /**
     * Retrieve the property name
     *
     * This method was designed to support old framework normalization
     * with the "_" underscore prefix character on property names.
     * The "_" should not be used in the PSR-2 standard
     *
     * @param string $name
     *
     * @return string|false
     */
    private function getProperty($name)
    {
        $normalized = lcfirst($name);
        $old = "_{$normalized}";
        $name = false;
        foreach ($this->getInspector()->getClassProperties() as $property) {
            if ($old == $property || $normalized == $property) {
                $name = $property;
                break;
            }
        }
        return $name;
    }
}