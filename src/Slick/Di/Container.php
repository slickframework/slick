<?php

/**
 * Dependency container
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Definition\Scope,
    Slick\Di\Resolver\AliasResolver,
    Slick\Di\Resolver\ValueResolver,
    Slick\Di\Resolver\CallableResolver,
    Slick\Di\Exception\NotFoundException,
    Slick\Di\Definition\DefinitionManager,
    Slick\Di\Exception\DependencyException,
    Slick\Di\Exception\InvalidArgumentException;
use Slick\Di\Resolver\ObjectResolver;

/**
 * Dependency container
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Container implements ContainerInterface, FactoryInterface
{

    /**
     * @var DefinitionManager
     */
    protected $_definitionManager;

    /**
     * Map of entries with Singleton scope that are already resolved.
     * @var array
     */
    protected static $_singletonEntries = [];

    /**
     * Map of definition resolvers, indexed by the class name of the
     * definition it resolves.
     *
     * @var ResolverInterface[]
     */
    protected $_definitionResolvers;

    /**
     * Creates a new container
     */
    public function __construct(DefinitionManager $definitionManager)
    {
        $this->_definitionManager = $definitionManager;

        $this->_definitionResolvers = [
            'Slick\Di\Definition\ValueDefinition' => new ValueResolver(),
            'Slick\Di\Definition\CallableDefinition' => new CallableResolver(),
            'Slick\Di\Definition\AliasDefinition' => new AliasResolver($this),
            'Slick\Di\Definition\ObjectDefinition' => new ObjectResolver($this),
        ];

        static::$_singletonEntries['Slick\Di\Container'] = $this;
        static::$_singletonEntries['Slick\Di\ContainerInterface'] = $this;
    }

    /**
     * Retrieves a container if it was already created
     *
     * @return false|Container False if there is no container created.
     */
    public static function getContainer()
    {
        $container = false;
        if (isset(static::$_singletonEntries['Slick\Di\Container'])) {
            $container = static::$_singletonEntries['Slick\Di\Container'];
        }
        return $container;
    }

    /**
     * Returns current definition manager
     *
     * @return DefinitionManager
     */
    public function getDefinitionManager()
    {
        return $this->_definitionManager;
    }

    /**
     * Returns an entry of the container by its name.
     *
     * @param string $name Entry name or a class name.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     * @throws DependencyException Error while resolving the entry.
     * @throws NotFoundException No entry found for the given name.
     *
     * @return mixed
     */
    public function get($name)
    {
        if (! is_string($name)) {
            throw new InvalidArgumentException(sprintf(
                'The name parameter must be of type string, %s given',
                is_object($name) ? get_class($name) : gettype($name)
            ));
        }

        // Try to find the entry in the singleton map
        if (array_key_exists($name, static::$_singletonEntries)) {
            return static::$_singletonEntries[$name];
        }

        $definition = $this->_definitionManager->get($name);
        if (! $definition) {
            throw new NotFoundException("No entry or class found for '$name'");
        }

        $value = $this->_resolveDefinition($definition);

        if ($definition->getScope() == Scope::SINGLETON()) {
            static::$_singletonEntries[$name] = $value;
        }

        return $value;
    }

    /**
     * Test if the container can provide something for the given name.
     *
     * @param string $name Entry name or a class name.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     *
     * @return bool
     */
    public function has($name)
    {
        if (! is_string($name)) {
            throw new InvalidArgumentException(sprintf(
                'The name parameter must be of type string, %s given',
                is_object($name) ? get_class($name) : gettype($name)
            ));
        }

        return array_key_exists($name, static::$_singletonEntries) ||
            $this->_definitionManager->has($name);

    }

    /**
     * Build an entry of the container by its name.
     *
     * This method behave like get() except it forces the scope to "prototype",
     * which means the definition of the entry will be re-evaluated each time.
     * For example, if the entry is a class, then a new instance will be
     * created each time.
     *
     * This method makes the container behave like a factory.
     *
     * @param string $name       Entry name or a class name.
     * @param array  $parameters Optional parameters to use to build the entry.
     *                           Use this to force specific parameters
     *                           to specific values. Parameters not defined in
     *                           this array will be resolved using
     *                           the container.
     *
     * @throws InvalidArgumentException Name parameter must be of type string.
     * @throws DependencyException      Error while resolving the entry.
     * @throws NotFoundException        No entry found for the given name.
     * @return mixed
     */
    public function make($name, array $parameters = [])
    {
        if (! is_string($name)) {
            throw new InvalidArgumentException(sprintf(
                'The name parameter must be of type string, %s given',
                is_object($name) ? get_class($name) : gettype($name)
            ));
        }

        $definition = $this->_definitionManager->get($name);
        if (! $definition) {
            throw new NotFoundException("No entry or class found for '$name'");
        }

        return $this->_resolveDefinition($definition, $parameters);

    }

    /**
     * Add a definition resolver to the list of resolvers
     *
     * @param string            $className
     * @param ResolverInterface $resolver
     *
     * @return Container
     * @throws Exception\InvalidArgumentException Class name does not exists
     */
    public function addResolver($className, ResolverInterface $resolver)
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException(
                "The cass '{$className} was not found."
            );
        }

        $this->_definitionResolvers[$className] = $resolver;
        return $this;
    }

    /**
     * Resolves a definition.
     *
     * Checks for circular dependencies while resolving the definition.
     *
     * @param DefinitionInterface $definition
     * @param array               $parameters
     *
     * @throws DependencyException Error while resolving the entry.
     * @return mixed
     */
    protected function _resolveDefinition(
        DefinitionInterface $definition, array $parameters = [])
    {
        $entryName = $definition->getName();
        $definitionResolver = $this->_getDefinitionResolver($definition);

        try {
            $value = $definitionResolver->resolve($definition, $parameters);
        } catch (\Exception $exception) {
            throw new DependencyException(
                "An error occurred while resolving " .
                "entry '{$entryName}': " . $exception->getMessage(),
                0,
                $exception
            );
        }

        return $value;
    }

    /**
     * Returns a resolver capable of handling the given definition.
     *
     * @param DefinitionInterface $definition
     *
     * @throws NotFoundException No definition resolver was found for
     *                           this type of definition.
     * @return ResolverInterface
     */
    protected function _getDefinitionResolver(DefinitionInterface $definition)
    {
        $definitionType = get_class($definition);

        if (! isset($this->_definitionResolvers[$definitionType])) {
            throw new NotFoundException(
                "No definition resolver was configured for definition " .
                "of type $definitionType"
            );
        }

        return $this->_definitionResolvers[$definitionType];
    }
}