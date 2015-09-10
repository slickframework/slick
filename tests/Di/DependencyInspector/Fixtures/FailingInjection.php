<?php

/**
 *This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector\Fixtures;

use Interop\Container\ContainerInterface;

/**
 * FailingInjection: a test class for auto-hire feature on DI
 *
 * @package Slick\Tests\Di\DependencyInspector\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class FailingInjection
{

    private $container;

    private $name;

    private $options;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, array $options)
    {
        $this->container = $container;
        $this->options = $options;
    }
}