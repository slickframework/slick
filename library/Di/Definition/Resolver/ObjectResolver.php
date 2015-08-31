<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition\Resolver;

use Slick\Di\Definition\DefinitionResolver;
use Slick\Di\Definition\ObjectDefinitionInterface;

interface ObjectResolver extends DefinitionResolver
{

    /**
     * Set the definition that this resolver will resolve
     *
     * @param ObjectDefinitionInterface $definition Definition to resolve
     *
     * @return $this|self
     */
    public function setDefinition($definition);
}