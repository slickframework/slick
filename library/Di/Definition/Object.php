<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Common\Inspector;
use Slick\Di\Definition\Resolver\Object as Resolver;
use Slick\Di\Definition\Resolver\ObjectResolver;
use Slick\Di\Exception\InvalidArgumentException;

/**
 * Object definition class
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $name          Definition name or key
 * @property string $className
 * @property object $instance
 * @property array  $constructArgs
 * @property array  $properties
 * @property array  $methods
 */
class Object extends AbstractDefinition implements ObjectDefinitionInterface
{
    /**
     * @readwrite
     * @var string
     */
    protected $className;

    /**
     * @readwrite
     * @var object
     */
    protected $instance;

    /**
     * @readwrite
     * @var array
     */
    protected $constructArgs = [];

    /**
     * @readwrite
     * @var array
     */
    protected $properties = [];

    /**
     * @readwrite
     * @var array
     */
    protected $methods = [];

    /**
     * @var Inspector
     */
    protected $classMetaData;

    /**
     * @readwrite
     * @var ObjectResolver
     */
    protected $resolver;

    /**
     * Gets class meta data (Inspector)
     *
     * @return Inspector
     */
    protected function getClassMetaData()
    {
        if (is_null($this->classMetaData)) {
            $this->classMetaData = Inspector::forClass($this->getClassName());
        }
        return $this->classMetaData;
    }

    /**
     * Gets definition class name
     *
     * If class name is not set and there is an instance set the class name
     * will be retrieved from instance object.
     *
     * @return string
     */
    public function getClassName()
    {
        if (is_null($this->className) && is_object($this->instance)) {
            $this->className = get_class($this->instance);
        }
        return $this->className;
    }

    /**
     * Gets the instance object for current definition
     *
     * If instance is not defined yet and the class name is set and
     * is an existing class, a new instance will be created and the
     * constructor arguments will be used.
     *
     * @return object
     */
    public function getInstance()
    {
        if (is_null($this->instance) && class_exists($this->className)) {
            $reflection = $this->getClassMetaData()->getReflection();
            $this->instance = (!empty($this->constructArgs))
                ? $reflection->newInstanceArgs($this->constructArgs)
                : new $this->className();
        }
        return $this->instance;
    }

    /**
     * Sets constructor arguments used on instance instantiation
     *
     * @param array $arguments
     * @return $this|self
     */
    public function setConstructArgs(array $arguments)
    {
        $this->constructArgs = $arguments;
        return $this;
    }

    /**
     * Set a method to be called when resolving this definition
     *
     * @param string $name      Method name
     * @param array  $arguments Method parameters
     *
     * @return $this|self
     */
    public function setMethod($name, array $arguments = [])
    {
        if (!$this->getClassMetaData()->hasMethod($name)) {
            throw new InvalidArgumentException(
                "The method {$name} does not exists in class ".
                "{$this->getClassName()}"
            );
        }

        $this->methods[$name] = $arguments;
        return $this;
    }

    /**
     * Sets property value when resolving this definition
     *
     * @param string $name  The property name
     * @param mixed  $value The property value
     *
     * @return $this|self
     */
    public function setProperty($name, $value)
    {
        if (!$this->getClassMetaData()->hasProperty($name)) {
            throw new InvalidArgumentException(
                "The property {$name} does not exists in class ".
                "{$this->getClassName()}"
            );
        }

        $this->properties[$name] = $value;
        return $this;
    }

    /**
     * Resolves current definition and returns its value
     *
     * @return mixed
     */
    public function resolve()
    {
        return $this->getResolver()->resolve();
    }

    /**
     * Gets property values
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Returns the list of methods to call
     *
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Returns resolver for this definition
     *
     * @return Resolver|ObjectResolver
     */
    protected function getResolver()
    {
        if (is_null($this->resolver)) {
            $this->resolver = new Resolver(
                [
                    'definition' => $this
                ]
            );
        }
        return $this->resolver;
    }
}