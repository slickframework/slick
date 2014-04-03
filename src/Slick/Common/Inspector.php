<?php

/**
 * Inspector
 * 
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Common;

use Slick\Common\Inspector\TagList;
use Slick\Utility\Text,
    Slick\Utility\ArrayMethods,
    Slick\Common\Inspector\ClassMetaData,
    Slick\Common\Exception;

/**
 * Inspector uses PHP reflection to inspect classes or objects.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Inspector
{

    /**
     * @var string|object Class name or object to inspect.
     */
    protected $_class = null;
    
    /**
     * @var \ReflectionClass The reflection data for given class.
     */
    protected $_reflection = null;

    /**
     * @var \Slick\Common\Inspector\ClassMetaData Class meta data object
     */
    protected $_metaData = null;

    /**
     * @var array Class metadata.
     */
    protected $_meta = array(
        "class" => array(),
        "properties" => array(),
        "methods" => array()
    );

    /**
     * @var \ArrayIterator List of class properties
     */
    protected $_properties = array();

    /**
     * @var \ArrayIterator List of class methods.
     */
    protected $_methods = array();

    /**
     * Constructs an inspector for a given class.
     *
     * @param string|object $class The class name or object to inspect.
     */
    public function __construct($class)
    {
        $this->_class = $class;
    }
    
    /**
     * Returns the inspected class meta data
     *
     * @return array A key/value(s) associative array from class comment.
     */
    public function getClassMeta()
    {
        return $this->_getClassMetaData()->getClassTags();
    }
    
    /**
     * Retrieves the list of class properties.
     * 
     * @return \ArrayIterator An array with property names.
     */
    public function getClassProperties()
    {
        if (empty($this->_properties)) {
            $properties = $this->_getReflection()->getProperties();
            $this->_properties = new \ArrayIterator();
            foreach ($properties as $property) {
                $this->_properties[] = $property->getName();
            }
        }
        return $this->_properties;
    }
    
    /**
     * Retrieves the list of class methods
     * 
     * @return array An array with method names.
     */
    public function getClassMethods()
    {
        if (empty($this->_methods)) {
            $methods = $this->_getReflection()->getMethods();
            $this->_methods = new \ArrayIterator();
            foreach ($methods as $method) {
                $this->_methods[] = $method->getName();
            }
        }
        return $this->_methods;
    }

    /**
     * Returns property meta data.
     *
     * @param string $property The property name to retrieve the meta data.
     *
     * @throws Exception\InvalidArgumentException
     * @return TagList A key/value(s) associative array from property comment.
     */
    public function getPropertyMeta($property)
    {
        if (!in_array($property, $this->getClassProperties()->getArrayCopy())) {
            $name = $this->_getReflection()->getName();
            throw new Exception\InvalidArgumentException(
                "The class {$name} doesn't have a property called {$property}"
            );
        }

        return $this->_getClassMetaData()->getPropertyMeta($property);
    }
    
    /**
     * Returns method meta data.
     *
     * @param string $method The method name to retrieve the meta data.
     * 
     * @return \Slick\Common\Inspector\TagList A list of tags of the inspected
     *  method
     */
    public function getMethodMeta($method)
    {
        if (!in_array($method, $this->getClassMethods()->getArrayCopy())) {
            $name = $this->_getReflection()->getName();
            throw new Exception\InvalidArgumentException(
                "The class {$name} doesn't have a method called {$method}"
            );
        }

        return $this->_getClassMetaData()->getMethodMeta($method);
    }

    /**
     * Checks if the methos is defined in the inpected class
     * 
     * @param string $name The method name to verify
     * 
     * @return boolean True if method is defined in the inspected class
     */
    public function hasMethod($name)
    {
        return in_array($name, $this->getClassMethods()->getArrayCopy());
    }
    
    /**
     * Returns the reflection object for inpected class
     * 
     * @return \ReflectionClass The reflection of given class
     */
    protected function _getReflection()
    {
        if (is_null($this->_reflection)) {
            $this->_reflection = new \ReflectionClass($this->_class);
        }
        return $this->_reflection;
    }

    /**
     * Returns the class meta data objcect for this class
     * 
     * @return \Slick\Common\Inspector\ClassMetaData
     */
    protected function _getClassMetaData()
    {
        if (is_null($this->_metaData)) {
            $this->_metaData = new ClassMetaData($this->_getReflection());
        }
        return $this->_metaData;
    }
    
}