<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Slick\Di\Definition\DefinitionList;
use Slick\Di\Definition\Factory;
use Slick\Di\Definition\Scope;
use Slick\Di\Definition\Value;
use Slick\Di\Exception\InvalidArgumentException;
use Slick\Di\Exception\NotFoundException;

/**
 * Dependency injection container
 *
 * @package Slick\Di
 * @author  Filipe Silva <filipe.silva@gmail.com>
 */
class Container implements ContainerInterface
{

    /**
     * @var DefinitionList
     */
    protected $definitions;

    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @readwrite
     * @var ContainerInterface
     */
    protected $parent;

    private static $containerKeys = [
        'Interop\Container\ContainerInterface',
        'Slick\Di\Container'
    ];

    /**
     * Initialise the container with an empty definitions list
     */
    public function __construct()
    {
        $this->definitions = new DefinitionList();
        $existingKey = reset(self::$containerKeys);
        if (array_key_exists($existingKey, static::$instances)) {
            $this->setParent(static::$instances[$existingKey]);
        }

        foreach (self::$containerKeys as $key) {
            $this->register($key, $this);
            static::$instances[$key] = $this;
        }
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException(
                "There is no entry with '{$id}' name in the ".
                "dependency container."
            );
        }

        $entry = (!$this->definitions->offsetExists($id))
            ? $this->parent->get($id)
            : $this->resolve($this->definitions[$id]);

        if (is_object($entry)) {
            $entry = $this->injectOn($entry);
        }

        return $entry;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        if (!is_string($id)) {
            return false;
        }

        if (!$this->definitions->offsetExists($id)) {
            return $this->parentHas($id);
        }
        return true;
    }

    /**
     * Adds a definition or a value to the container
     *
     * If the $definition parameter is a scalar a Value definition is created
     * and added to the definitions list.
     *
     * If the $value is a callable a factory definition will be created
     *
     * @param string|DefinitionInterface $definition
     * @param mixed                      $value
     * @param array                      $parameters
     * @param Scope|string               $scope
     *
     * @return $this|self
     */
    public function register(
        $definition, $value = null, array $parameters = [],
        $scope = Scope::SINGLETON)
    {
        if (! $definition instanceof DefinitionInterface) {
            if (is_callable($value)) {
                $value = $this->createFactoryDefinition(
                    (string) $definition,
                    $value,
                    $parameters,
                    new Scope((string) $scope)
                );
            }

            $definition = ($value instanceof DefinitionInterface)
                ? $value->setName($definition)
                : $definition = $this->createValueDefinition(
                    (string) $definition,
                    $value
                );
        }

        $definition->setContainer($this);

        $this->definitions->append($definition);
        return $this;
    }

    /**
     * Creates the object for provided class name
     *
     * This method creates factory definition that can be retrieved from
     * the container by using it FQ class name.
     *
     * If there are satisfiable dependencies in the container the are injected.
     *
     * @param string $className  FQ class name
     * @param array  $parameters An array of constructor parameters
     * @param string $scope      The definition scope
     *
     * @return mixed
     */
    public function make(
        $className, array $parameters = [], $scope = Scope::SINGLETON)
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(
                "DI container cannot make object. Class does not exists."
            );
        }

        if (!$this->has($className)) {
            $this->registerObject($className, $parameters, $scope);
        }

        return $this->get($className);
    }

    /**
     * Inject known dependencies on provide object
     *
     * @param object $object
     *
     * @return mixed
     */
    public function injectOn($object)
    {
        $injectedObject = $object;
        $inspector = new DependencyInspector($this, $object);
        $definition = $inspector->getDefinition();
        if ($inspector->isSatisfiable()) {
            $injectedObject = $definition->resolve();
        }
        return $injectedObject;
    }

    /**
     * Set parent container for interoperability
     *
     * @ignoreInject
     * @param ContainerInterface $container
     * @return $this
     */
    public function setParent(ContainerInterface $container)
    {
        $this->parent = $container;
        return $this;
    }

    /**
     * Creates a value definition for register
     *
     * @param string $name  The name for the definition
     * @param mixed  $value The value that the definition will resolve
     *
     * @return Value A Value definition object
     */
    private function createValueDefinition($name, $value)
    {
        return new Value(
            [
                'name' => $name,
                'value' => $value
            ]
        );
    }

    /**
     * Creates a Factory definition
     *
     * @param string   $name
     * @param callable $callback
     * @param array    $params
     * @param Scope    $scope
     *
     * @return Factory
     */
    private function createFactoryDefinition(
        $name, Callable $callback, array $params, Scope $scope)
    {
        $definition = new Factory(['name' => $name]);
        return $definition
            ->setCallable($callback, $params)
            ->setScope($scope);
    }

    /**
     * Returns the resolved object for provide definition
     *
     * @param DefinitionInterface $definition
     * @return mixed|null
     */
    private function resolve(DefinitionInterface $definition)
    {
        if ($definition->getScope() == Scope::Prototype()) {
            return $definition->resolve();
        }
        return $this->resolveSingleton($definition);
    }

    /**
     * Resolve instances and save then for singleton use
     *
     * @param DefinitionInterface $definition
     * @return mixed
     */
    private function resolveSingleton(DefinitionInterface $definition)
    {
        $key = $definition->getName();
        $hasInstance = array_key_exists($key, static::$instances);

        if (!$hasInstance) {
            static::$instances[$key] = $definition->resolve();
        }
        return static::$instances[$key];
    }

    /**
     * Creates and registers an object definition
     *
     * @param string       $name       FQ class name
     * @param array        $parameters Constructor parameters
     * @param string $scope      Definition scope
     *
     * @return $this|self
     */
    private function registerObject($name, $parameters, $scope)
    {
        $inspector = new DependencyInspector($this, $name);
        $definition = $inspector->getDefinition();
        if (!empty($parameters)) {
            $definition->setConstructArgs($parameters);
        }
        $definition->setScope(new Scope((string) $scope));
        $this->register($definition);
        return $this;
    }

    /**
     * Check if parent has provided key
     *
     * @param string $key
     * @return bool
     */
    private function parentHas($key)
    {
        if (is_null($this->parent)) {
            return false;
        }
        return $this->parent->has($key);
    }
}
