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
use ReflectionClass;
use Slick\Di\Definition\Factory;
use Slick\Di\Definition\Scope;
use Slick\Di\Exception\InvalidArgumentException;
use Slick\Di\Exception\NotFoundException;
use Slick\Di\Definition\DefinitionList;
use Slick\Di\Definition\Value;

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
     * Initialise the container with an empty definitions list
     */
    public function __construct()
    {
        $this->definitions = new DefinitionList();
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
                "There is no entry with '{$id}' name in the " .
                "dependency container."
            );
        }

        return $this->resolve($this->definitions[$id]);
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
        return $this->definitions->offsetExists($id);
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
        if (is_callable($value)) {
            $definition = $this->createFactoryDefinition(
                $definition,
                $value,
                $parameters,
                new Scope((string) $scope)
            );
        }

        if (!($definition instanceof DefinitionInterface)) {
            $definition = $this->createValueDefinition($definition, $value);
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
            $this->registerFactory($className, $parameters, $scope);
        }

        return $this->get($className);
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
        return (new Factory(['name' => $name]))
            ->setScope($scope)
            ->setCallable($callback, $params);
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
     * Creates and registers a factory definition
     *
     * @param string       $name       FQ class name
     * @param array        $parameters Constructor parameters
     * @param Scope|string $scope      Definition scope
     *
     * @return $this|self
     */
    private function registerFactory($name, $parameters, $scope)
    {
        $closure = function($name, array $parameters=[]) {
            $classReflection = new ReflectionClass($name);
            return $classReflection->newInstanceArgs($parameters);
        };
        $definition = (new Factory(['name' => $name]))
            ->setScope(new Scope((string) $scope))
            ->setCallable($closure, [$name, $parameters]);
        $this->register($definition);
        return $this;
    }
}
