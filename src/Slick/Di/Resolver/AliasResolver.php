<?php

/**
 * AliasResolver
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Resolver;

use Slick\Di\ResolverInterface,
    Slick\Di\ContainerInterface,
    Slick\Di\DefinitionInterface,
    Slick\Di\Definition\AliasDefinition;

/**
 * AliasResolver
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AliasResolver implements ResolverInterface
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
        /** @var AliasDefinition $definition */
        return $this->_container->get($definition->getTargetEntryName());
    }
}