<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Interop\Container\ContainerInterface;
use Slick\Common\Base;
use Slick\Di\DefinitionInterface;

/**
 * A base class for all definitions
 *
 * @package Slick\Tests\Di\Definition
 *
 * @property string $name  Definition name or key
 * @property Scope  $scope Definition scope: Prototype or Singleton
 *
 * @property $this|AbstractDefinition setName(string $name) Sets definition
 *                                                          name.
 */
abstract class AbstractDefinition extends Base implements DefinitionInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $name;

    /**
     * @readwrite
     * @var Scope
     */
    protected $scope;

    /**
     * @readwrite
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Initializes the
     * @param array $options
     */
    public function __construct($options = [])
    {
        $options = array_replace(
            [
                'scope' => new Scope(Scope::SINGLETON)
            ],
            $options
        );
        parent::__construct($options);
    }

    /**
     * Gets current definition name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the scope for current definition
     *
     * @return Scope
     */
    public function getScope()
    {
        return new Scope((string) $this->scope);
    }

    /**
     * Sets definition scope
     *
     * @param Scope $scope The scope type
     * @return $this|self
     */
    public function setScope(Scope $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Set container for this definition
     *
     * @param ContainerInterface $container
     *
     * @return $this|self
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Gets container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
