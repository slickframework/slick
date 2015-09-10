<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Di\Fixtures;
use Interop\Container\ContainerInterface;

/**
 * Injectable class for tests
 *
 * @package Di\Fixtures
 */
class InjectableClass
{

    /**
     * @inject name
     * @var string
     */
    public $name = '';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @inject
     * @var \Interop\Container\ContainerInterface
     */
    protected $injectedContainer;

    /**
     * @var ContainerInterface
     */
    protected $otherContainer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->otherContainer = $container;
        return $this;
    }
}