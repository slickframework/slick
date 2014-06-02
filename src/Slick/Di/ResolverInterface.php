<?php

/**
 * ResolverInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

/**
 * Resolve a definition to a value.
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ResolverInterface
{

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
        DefinitionInterface $definition, array $parameters = []);
} 