<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface;
use Slick\Common\Base;
use Slick\Di\Definition\Object as ObjectDefinition;
use Slick\Di\Definition\ObjectDefinitionInterface;
use Slick\Di\DependencyInspector\ConstructorInspector;
use Slick\Di\DependencyInspector\MethodsInspector;
use Slick\Di\DependencyInspector\PropertiesInspector;
use Slick\Di\Exception\InvalidArgumentException;

/**
 * Inspects a class to determine the dependencies that can be injected
 *
 * @package Slick\Di
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $className   The class name to inspect
 * @property object $instance    The instance to inspect
 * @property bool   $satisfiable Flag for definition resolution
 *
 * @property ContainerInterface $container Container with dependencies
 *
 * @method bool isSatisfiable() Check if definition can be resolved
 */
final class DependencyInspector extends Base
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
     * @write
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @readwrite
     * @var boolean
     */
    protected $satisfiable = true;

    /**
     * @readwrite
     * @var ConstructorInspector
     */
    protected $constructorInspector;

    /**
     * @readwrite
     * @var MethodsInspector
     */
    protected $methodsInspector;

    /**
     * @readwrite
     * @var PropertiesInspector
     */
    protected $propertiesInspector;

    /**
     * Set dependency on a container and class name or object.
     *
     * @param ContainerInterface $container The container to work with
     * @param object|string      $class     FQ class name or object
     * @param array              $options   Additional property values
     *
     * @throws InvalidArgumentException If the provided class name is from a
     *                                  class that does not exists.
     */
    public function __construct(
        ContainerInterface $container, $class, array $options = [])
    {
        $className = is_string($class) ? $class : null;
        $instance = is_object($class) ? $class : null;
        $options = array_replace(
            [
                'container' => $container,
                'className' => $className,
                'instance'  => $instance
            ],
            $options
        );
        parent::__construct($options);
    }

    /**
     * Gets the object definition with dependencies injected
     *
     * @returns ObjectDefinitionInterface
     */
    public function getDefinition()
    {
        $definition = new ObjectDefinition(
            [
                'className' => $this->className,
                'instance' => $this->instance,
                'container' => $this->container
            ]
        );
        if (is_null($this->instance)) {
            $definition->name = $this->className;
            $this->getConstructorInspector()->setDefinition($definition);
            $this->satisfiable = $this->getConstructorInspector()
                ->isSatisfiable();
        }

        if ($this->satisfiable) {
            $this->getMethodsInspector()->setDefinition($definition);
            $this->getPropertiesInspector()->setDefinition($definition);
        }
        return $definition;
    }

    /**
     * Sets the class name to be inspected
     *
     * @param string $className
     *
     * @return $this|self
     *
     * @throws InvalidArgumentException If the provided class name is from a
     *                                  class that does not exists.
     */
    public function setClassName($className)
    {
        if (!is_null($className) && !class_exists($className)) {
            throw new InvalidArgumentException(
                "The class '{$className}' does not exists."
            );
        }

        $this->className = $className;
        return $this;
    }

    /**
     * Gets a constructor inspector
     *
     * @return ConstructorInspector
     */
    protected function getConstructorInspector()
    {
        if (is_null($this->constructorInspector)) {
            $this->constructorInspector = new ConstructorInspector();
        }
        return $this->constructorInspector;
    }

    /**
     * Gets the methods inspector
     *
     * @return MethodsInspector
     */
    protected function getMethodsInspector()
    {
        if (is_null($this->methodsInspector)) {
            $this->methodsInspector = new MethodsInspector();
        }
        return $this->methodsInspector;
    }

    /**
     * Gets properties inspector
     *
     * @return PropertiesInspector
     */
    protected function getPropertiesInspector()
    {
        if (is_null($this->propertiesInspector)) {
            $this->propertiesInspector = new PropertiesInspector();
        }
        return $this->propertiesInspector;
    }
}
