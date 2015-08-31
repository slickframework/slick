<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Interface defines a definition resolver
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface DefinitionResolver
{

    /**
     * Set the definition that this resolver will resolve
     *
     * @param DefinitionInterface $definition Definition to resolve
     *
     * @return $this|self
     */
    public function setDefinition($definition);

    /**
     * Resolves provided definition
     *
     * @return mixed
     */
    public function resolve();

}