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

use Slick\Utility\Text,
    Slick\Utility\ArrayMethods;

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
     * @var array Class metadata.
     */
    protected $_meta = array(
        "class" => array(),
        "properties" => array(),
        "methods" => array()
    );

    /**
     * @var array List of class properties
     */
    protected $_properties = array();

    /**
     * @var array List of class methods.
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
        if (empty($this->_meta['class'])) {
            $comment = $this->_getReflection()->getDocComment();

            if (!empty($comment)) {
                $this->_meta['class'] = $this->_parse($comment);
            } else {
                $this->_meta['class'] = array();
            }
        }
        return $this->_meta['class'];
    }
    
    /**
     * Retrives the list of class properties.
     * 
     * @return array An array with property names.
     */
    public function getClassProperties()
    {
        if (empty($this->_properties)) {
            $properties = $this->_getReflection()->getProperties();
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
     * @return array A key/value(s) associative array from property comment.
     */
    public function getPropertyMeta($property)
    {
        if (!isset($this->_meta['properties'][$property])) {
            $comment = $this->_getReflection()
                ->getProperty($property)
                ->getDocComment();
            if (!empty($comment)) {
                $this->_meta['properties'][$property] = $this->_parse($comment);
            } else {
                $this->_meta['properties'][$property] = null;
            }
        }
        return $this->_meta['properties'][$property];
    }
    
    /**
     * Returns method meta data.
     *
     * @param string $method The method name to retrieve the meta data.
     * 
     * @return array A key/value(s) associative array from method comment.
     */
    public function getMethodMeta($method)
    {
        if (!isset($this->_meta['methods'][$method])) {
            $comment = $this->_getReflection()->getMethod($method)->getDocComment();
            if (!empty($comment)) {
                $this->_meta['methods'][$method] = $this->_parse($comment);
            } else {
                $this->_meta['methods'][$method] = null;
            }
        }
        return $this->_meta['methods'][$method];
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
     * Parses the docblock comment to retrieve anotaion tags like @tag.
     *
     * It will scan the comment to find anotation tags and creates an
     * array of (@tag name as key) tag values. If a tag with no
     * value is found, it will have a boolean TRUE value.
     * 
     * @param string $comment The comment to parse.
     * 
     * @return array A key/value(s) associative array from comment.
     */
    protected function _parse($comment)
    {
        $meta = array();
        $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9\\\,=\s\.\@\<\>_\-]*)";
        
        $matches = Text::match($comment, $pattern);
        if ($matches != null) {
            foreach ($matches as $match) {
                $parts = ArrayMethods::clean(
                    ArrayMethods::trim(Text::split($match, "[\s*]", 2))
                );

                $meta[$parts[0]] = true;

                if (sizeof($parts) > 1) {
                    $meta[$parts[0]] = ArrayMethods::clean(
                        ArrayMethods::trim(Text::split($parts[1], ","))
                    );
                }
            }
        }
        return $meta;
    }
}