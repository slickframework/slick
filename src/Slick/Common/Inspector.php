<?php

/**
 * Inspector
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Slick\Common;

use ReflectionClass;
use Slick\Common\Inspector\AnnotationsList;
use Slick\Common\Inspector\AnnotationParser;
use Slick\Common\Inspector\AnnotationInterface;
use Slick\Common\Exception\InvalidArgumentException;

/**
 * Inspector uses PHP reflection to inspect classes or objects.
 *
 * @package    Slick\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Inspector
{

    /**
     * @var String|Object
     */
    private $_class = null;

    /**
     * @var ReflectionClass
     */
    private $_reflection = null;

    /**
     * @var string[]
     */
    private $_properties = [];

    /**
     * @var string[]
     */
    private $_methods = [];

    /**
     * @var array
     */
    private $_annotations = [
        'class' => [],
        'properties' => [],
        'methods' => []
    ];

    /**
     * @var string[]
     */
    private static $_classMap = [
        'default' => '\Slick\Common\Inspector\Annotation'
    ];

    /**
     * Constructs an inspector for a given class.
     *
     * @param String|Object $class The class name or object to inspect.
     */
    public function __construct($class)
    {
        $this->_class = $class;
    }

    /**
     * Retrieves the list of annotations from inspected class
     *
     * @return AnnotationsList
     */
    public function getClassAnnotations()
    {
        if (empty($this->_annotations['class'])) {
            $comment = $this->_getReflection()->getDocComment();
            $data = AnnotationParser::getAnnotations($comment);
            $classAnnotations = new AnnotationsList();
            foreach ($data as $name => $parsedData) {
                $classAnnotations->append(
                    $this->_createAnnotation($name, $parsedData)
                );
            }
            $this->_annotations['class'] = $classAnnotations;
        }
        return $this->_annotations['class'];
    }

    /**
     * Retrieves the list of annotations from provided property
     *
     * @param string $property Property name
     * @throws Exception\InvalidArgumentException
     * @return AnnotationsList
     */
    public function getPropertyAnnotations($property)
    {
        if (!$this->hasProperty($property)) {
            $name = $this->_getReflection()->getName();
            throw new Exception\InvalidArgumentException(
                "The class {$name} doesn't have a property called {$property}"
            );
        }

        if (empty($this->_annotations['properties'][$property])) {
            $comment = $this->_getReflection()
                ->getProperty($property)
                ->getDocComment();
            $data = AnnotationParser::getAnnotations($comment);
            $propertyAnnotations = new AnnotationsList();
            foreach ($data as $property => $parsedData) {
                $propertyAnnotations->append(
                    $this->_createAnnotation($property, $parsedData)
                );
            }
            $this->_annotations['properties'][$property] = $propertyAnnotations;
        }
        return $this->_annotations['properties'][$property];
    }

    /**
     * Retrieves the list of annotations of provided methods
     *
     * @param string $method
     * @return AnnotationsList
     *
     * @throws Exception\InvalidArgumentException
     */
    public function getMethodAnnotations($method)
    {
        if (!$this->hasMethod($method)) {
            $name = $this->_getReflection()->getName();
            throw new Exception\InvalidArgumentException(
                "The class {$name} doesn't have a property called {$method}"
            );
        }

        if (empty($this->_annotations['methods'][$method])) {
            $comment = $this->_getReflection()
                ->getMethod($method)
                ->getDocComment();
            $data = AnnotationParser::getAnnotations($comment);
            $methodAnnotations = new AnnotationsList();
            foreach ($data as $property => $parsedData) {
                $methodAnnotations->append(
                    $this->_createAnnotation($property, $parsedData)
                );
            }
            $this->_annotations['methods'][$method] = $methodAnnotations;
        }
        return $this->_annotations['methods'][$method];
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
     * Checks if the method is defined in the inspected class
     *
     * @param string $name The method name to verify
     *
     * @return boolean True if method is defined in the inspected class
     */
    public function hasMethod($name)
    {
        return $this->_getReflection()->hasMethod($name);
    }

    /**
     * Checks if the property is defined in the inspected class
     *
     * @param string $name The property name to verify
     *
     * @return boolean True if property is defined in the inspected class
     */
    public function hasProperty($name)
    {
        return $this->_getReflection()->hasProperty($name);
    }

    /**
     * Adds a class to the annotations class map
     *
     * @param string $name
     * @param string $class
     * @throws Exception\InvalidArgumentException
     */
    public static function addAnnotationClass($name, $class)
    {
        $reflection = new ReflectionClass($class);
        if (
            !$reflection->implementsInterface(
                'Slick\Common\Inspector\AnnotationInterface'
            )
        ) {
            throw new InvalidArgumentException(
                "{$class} does not implement " .
                "Slick\Common\Inspector\AnnotationInterface interface"
            );
        }
        static::$_classMap[$name] = $class;
    }

    /**
     * Returns the reflection object for inspected class
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
     * Creates the correct annotation object
     *
     * @param string $name
     * @param mixed $parsedData
     * @return AnnotationInterface
     */
    protected function _createAnnotation($name, $parsedData)
    {
        $class = static::$_classMap['default'];
        if (isset(static::$_classMap[$name])) {
            $class = static::$_classMap[$name];
        }

        $classReflection = new ReflectionClass($class);
        return $classReflection->newInstanceArgs([$name, $parsedData]);
    }
} 