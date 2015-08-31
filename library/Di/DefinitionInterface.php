<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di;

use Interop\Container\ContainerInterface;
use Slick\Di\Definition\Scope;

/**
 * Definition for container resolution
 *
 * @package Slick\Di
 */
interface DefinitionInterface
{

    /**
     * Resolves current definition and returns its value
     *
     * @return mixed
     */
    public function resolve();

    /**
     * Gets current definition name
     *
     * @return mixed
     */
    public function getName();

    /**
     * Gets the scope for current definition
     *
     * @return Scope
     */
    public function getScope();

    /**
     * Sets definition scope
     *
     * @param Scope $scope The scope type
     * @return $this|self
     */
    public function setScope(Scope $scope);

    /**
     * Set container for this definition
     *
     * @param ContainerInterface $container
     *
     * @return $this|self
     */
    public function setContainer(ContainerInterface $container);

    /**
     * Gets container
     *
     * @return ContainerInterface
     */
    public function getContainer();
}
