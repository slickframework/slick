<?php

/**
 * Dependency container builder
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Definition\AliasDefinition,
    Slick\Di\Definition\ValueDefinition,
    Slick\Di\Definition\DefinitionManager,
    Slick\Di\Definition\DefinitionHelperInterface,
    Slick\Di\Definition\ObjectDefinition\EntryReference;

/**
 * Dependency container builder
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerBuilder
{

    /**
     * Builds a dependency container with provided definitions
     *
     * @param array $definitions
     *
     * @return Container
     */
    public static function buildContainer(array $definitions)
    {
        /** @var ContainerBuilder $builder */
        $builder = new static();
        $container = Container::getContainer();
        $manager = $builder->_createManager($definitions);
        if (!$container) {
            $container = new Container($manager);
        } else {
            $builder->_merge($container->getDefinitionManager(), $manager);
        }
        return $container;
    }

    /**
     * Creates a definition manager for provided definitions
     *
     * @param array $definitions
     *
     * @return DefinitionManager
     */
    protected function _createManager(array $definitions)
    {
        $manager = new DefinitionManager();

        foreach ($definitions as $name => $definition)
        {
            if ($definition instanceof DefinitionHelperInterface) {
                /** @var DefinitionHelperInterface $definition */
                $manager->add($definition->getDefinition($name));
            } else if ($definition instanceof EntryReference) {
                /** @var EntryReference $definition */
                $manager->add(new AliasDefinition($name, $definition->getName()));
            } else {
                $manager->add(new ValueDefinition($name, $definition));
            }
        }
        return $manager;
    }

    /**
     * Adds new definitions to the current definitions manager
     *
     * @param DefinitionManager $current
     * @param DefinitionManager $new
     */
    protected function _merge(DefinitionManager $current, DefinitionManager $new)
    {
        /** @var DefinitionInterface $definition */
        foreach ($new as $definition) {
            if (!$current->has($definition->getName())) {
                $current->add($definition);
            }
        }
    }
} 