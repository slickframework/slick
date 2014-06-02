<?php

/**
 * ObjectResolver
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Resolver;

use Slick\Di\ContainerAwareInterface;
use Slick\Di\Exception\DependencyException;
use Slick\Di\ResolverInterface,
    Slick\Di\ContainerInterface,
    Slick\Di\DefinitionInterface,
    Slick\Di\Definition\ObjectDefinition,
    Slick\Di\Exception\NotFoundException,
    Slick\Di\Exception\DefinitionException,
    Slick\Di\Definition\ObjectDefinition\MethodInjection,
    Slick\Di\Definition\ObjectDefinition\PropertyInjection;

use ReflectionClass,
    ReflectionMethod,
    ReflectionParameter,
    ReflectionException;

/**
 * Resolves an object definition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ObjectResolver implements ResolverInterface
{

    /**
     * @var ContainerInterface
     */
    protected $_container;

    /**
     * Needs a container to target
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * Resolve a definition to a value.
     *
     * @param DefinitionInterface $definition Object that defines how the value
     *                                        should be obtained.
     * @param array               $parameters Optional parameters to use to
     *                                        build the entry.
     *
     * @return mixed Value obtained from the definition.
     */
    public function resolve(
        DefinitionInterface $definition, array $parameters = [])
    {
        /** @var ObjectDefinition $definition  */
        $instance =  $this->_createInstance($definition, $parameters);
        $this->_injectMethodsAndProperties($definition, $instance);
        $this->_checkContainerAware($instance);
        return $instance;
    }

    /**
     * Assigns the container for objects implementing ContainerAwareInterface
     *
     * @param mixed $instance The instance to check
     */
    public function _checkContainerAware($instance)
    {
        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($this->_container);
        }
    }

    /**
     * Creates an instance of the class and injects dependencies.
     *
     * @param ObjectDefinition $definition
     * @param array            $parameters Optional parameters to use to create
     *                                     the instance.
     *
     * @throws NotFoundException If class in definition is not found
     * @return object
     */
    protected function _createInstance(
        ObjectDefinition $definition, array $parameters)
    {
        $classReflection = new ReflectionClass($definition->getClassName());

        $args = $this->_getMethodParameters(
            $definition->getConstructor(),
            $classReflection->getConstructor(),
            $parameters
        );

        return $classReflection->newInstanceArgs($args);
    }

    /**
     * Returns the parameters for the provided method
     *
     * @param MethodInjection  $method
     * @param ReflectionMethod $methodReflection
     * @param array            $parameters
     *
     * @throws DefinitionException Parameter has no value and cannot be guessed
     * @return array
     */
    protected function _getMethodParameters(
        MethodInjection $method = null,
        ReflectionMethod $methodReflection = null,
        array $parameters = []
    ) {
        $args = [];
        if ($methodReflection) {
            foreach ($methodReflection->getParameters() as $index => $parameter) {
                if (array_key_exists($parameter->getName(), $parameters)) {
                    $value = $parameters[$parameter->getName()];
                } elseif ($method && $method->hasParameter($index)) {
                    $value = $method->getParameter($index);
                } else {
                    // If the parameter is optional and wasn't specified,
                    // we take its default value
                    if ($parameter->isOptional()) {
                        $args[] = $this->_getParameterDefaultValue(
                            $parameter,
                            $methodReflection
                        );
                        continue;
                    }

                    throw new DefinitionException(sprintf(
                        "The parameter '%s' of %s::%s has no value defined " .
                        "or guessable",
                        $parameter->getName(),
                        $methodReflection->getDeclaringClass()->getName(),
                        $methodReflection->getName()
                    ));
                }

                if ($value instanceof ObjectDefinition\EntryReference) {
                    $args[] = $this->_container->get($value->getName());
                } else {
                    $args[] = $value;
                }
            }
        }
        return $args;
    }

    /**
     * Returns the default value of a function parameter.
     *
     * @param ReflectionParameter $reflectionParameter
     * @param ReflectionMethod    $reflectionMethod
     *
     * @throws DefinitionException Can't get default values from PHP internal
     *                             classes and methods.
     * @return mixed
     * @codeCoverageIgnore
     */
    protected function _getParameterDefaultValue(
        ReflectionParameter $reflectionParameter,
        ReflectionMethod $reflectionMethod
    ) {
        try {
            return $reflectionParameter->getDefaultValue();
        } catch (ReflectionException $e) {
            throw new DefinitionException(sprintf(
                "The parameter '%s' of %s::%s has no type defined or " .
                "guessable. It has a default value, but the default value " .
                "can't be read through Reflection because it is a " .
                "PHP internal class.",
                $reflectionParameter->getName(),
                $reflectionMethod->getDeclaringClass()->getName(),
                $reflectionMethod->getName()
            ));
        }
    }

    /**
     * Inject properties and methods on the provided instance
     *
     * @param DefinitionInterface $definition
     * @param object              $instance
     */
    protected function _injectMethodsAndProperties(
        DefinitionInterface $definition, $instance)
    {
        $classReflection = new ReflectionClass($instance);

        /** @var ObjectDefinition $definition */

        // Property injections
        foreach ($definition->getProperties() as $property)
        {
            $this->_propertyInjection($instance, $property, $classReflection);
        }

        // Method injections
        foreach ($definition->getMethods() as $method) {
            $this->_methodInjection($instance, $method, $classReflection);
        }
    }

    /**
     * Method injection
     *
     * @param Object $instance
     * @param MethodInjection $method
     * @param ReflectionClass $classReflection
     */
    protected function _methodInjection(
        $instance, MethodInjection $method, ReflectionClass $classReflection)
    {

        $methodReflection = $classReflection->getMethod($method->getMethodName());
        $args = $this->_getMethodParameters($method, $methodReflection);
        $methodReflection->invokeArgs($instance, $args);
    }

    /**
     * Injects property value
     *
     * @param Object            $instance
     * @param PropertyInjection $property
     * @param ReflectionClass   $classReflection
     *
     * @throws DependencyException When an error occurs retrieving the value
     *                             from dependency container
     */
    protected function _propertyInjection(
        $instance, PropertyInjection $property, ReflectionClass $classReflection)
    {
        // lets try to confirm it not prefixed by convention
        $public = trim($property->getPropertyName(), '_');
        $notPublic = "_{$public}";
        $propertyName = $classReflection->hasProperty($notPublic) ? $notPublic : $public;

        $propertyReflection = $classReflection->getProperty($propertyName);

        $value = $property->getValue();

        if ($value instanceof ObjectDefinition\EntryReference) {
            $value = $this->_container->get($value->getName());
        }

        if (!$propertyReflection->isPublic()) {
            $propertyReflection->setAccessible(true);
        }

        $propertyReflection->setValue($instance, $value);
    }
}