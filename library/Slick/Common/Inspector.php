<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common;

use ReflectionClass;
use Slick\Common\Annotation\Factory;
use Slick\Common\Inspector\InspectorList;
use Slick\Common\Annotation\AnnotationList;
use Slick\Common\Exception\InvalidArgumentException;

/**
 * Inspector uses PHP reflection to inspect classes or objects.
 *
 * Used to store all information about a class including, properties,
 * methods, class annotations, property annotations and method annotations.
 *
 * @package Slick\Common
 */
class Inspector
{

    /**
     * @var string|object The object or class name that will be inspected
     */
    private $class;

    /**
     * @var string[]
     */
    private $properties = [];

    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @var array
     */
    private $annotations = [
        'class' => null,
        'properties' => [],
        'methods' => []
    ];

    /**
     * @var ReflectionClass
     */
    private $reflection = null;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * Constructs an inspector for a given class.
     *
     * @param String|Object $class The class name or object to inspect.
     */
    private function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Returns an inspector for provided class
     *
     * @param String|Object $class The class name or object to inspect.
     *
     * @return Inspector A new or reused inspector for provided class.
     */
    public static function forClass($class)
    {
        if (!InspectorList::getInstance()->has($class)) {
            $inspector = new Inspector($class);
            InspectorList::getInstance()->add($inspector);
        }
        return InspectorList::getInstance()->get($class);
    }

    /**
     * Retrieves the list of annotations from inspected class
     *
     * @return AnnotationList
     */
    public function getClassAnnotations()
    {
        if (is_null($this->annotations['class'])) {
            $comment = $this->getReflection()->getDocComment();
            $this->annotations['class'] = $this->getFactory()
                ->getAnnotationsFor($comment);
        }
        return $this->annotations['class'];
    }

    /**
     * Retrieves the list of annotations from provided property
     *
     * @param string $property Property name
     * @throws InvalidArgumentException
     * @return AnnotationList
     */
    public function getPropertyAnnotations($property)
    {
        if (!$this->hasProperty($property)) {
            $name = $this->getReflection()->getName();
            throw new InvalidArgumentException(
                "The class {$name} doesn't have a property called {$property}"
            );
        }

        if (!isset($this->annotations['properties'][$property])) {
            $comment = $this->getReflection()
                ->getProperty($property)
                ->getDocComment();
            $this->annotations['properties'][$property] = $this->getFactory()
                ->getAnnotationsFor($comment);
        }

        return $this->annotations['properties'][$property];
    }

    /**
     * Retrieves the list of annotations of provided methods
     *
     * @param string $method
     * @return AnnotationList
     *
     * @throws Exception\InvalidArgumentException
     */
    public function getMethodAnnotations($method)
    {
        if (!$this->hasMethod($method)) {
            $name = $this->getReflection()->getName();
            throw new InvalidArgumentException(
                "The class {$name} doesn't have a method called {$method}"
            );
        }

        if (!isset($this->annotations['methods'][$method])) {
            $comment = $this->getReflection()
                ->getMethod($method)
                ->getDocComment();

            $this->annotations['methods'][$method] = $this->getFactory()
                ->getAnnotationsFor($comment);
        }

        return $this->annotations['methods'][$method];
    }

    /**
     * Returns the class bind to this inspector
     *
     * @return object|string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Retrieves the list of class properties.
     *
     * @return \ArrayIterator An array with property names.
     */
    public function getClassProperties()
    {
        if (empty($this->properties)) {
            $properties = $this->getReflection()->getProperties();
            foreach ($properties as $property) {
                $this->properties[] = $property->getName();
            }
        }
        return $this->properties;
    }

    /**
     * Retrieves the list of class methods
     *
     * @return array An array with method names.
     */
    public function getClassMethods()
    {
        if (empty($this->methods)) {
            $methods = $this->getReflection()->getMethods();
            foreach ($methods as $method) {
                $this->methods[] = $method->getName();
            }
        }
        return $this->methods;
    }

    /**
     * Checks if the property is defined in the inspected class
     *
     * @param string $property The property name to verify
     *
     * @return boolean True if property is defined in the inspected class
     */
    public function hasProperty($property)
    {
        return $this->getReflection()->hasProperty($property);
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
        return $this->getReflection()->hasMethod($name);
    }

    /**
     * Return current class reflection object
     *
     * @return ReflectionClass
     */
    private function getReflection()
    {
        if (is_null($this->reflection)) {
            $this->reflection = new ReflectionClass($this->getClass());
        }
        return $this->reflection;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        if (is_null($this->factory)) {
            $factory = new Factory();
            $factory->setReflection($this->getReflection());
            $this->setFactory($factory);
        }
        return $this->factory;
    }

    /**
     * @param Factory $factory
     *
     * @return Inspector
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
        return $this;
    }




}