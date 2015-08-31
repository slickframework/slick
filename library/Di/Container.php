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
use Slick\Di\Definition\Scope;
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
     * @param string|DefinitionInterface $definition
     * @param mixed                      $value
     *
     * @return $this|self
     */
    public function register($definition, $value = null)
    {
        if (!($definition instanceof DefinitionInterface)) {
            $definition = $this->createValueDefinition($definition, $value);
        }

        $this->definitions->append($definition);
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
}
