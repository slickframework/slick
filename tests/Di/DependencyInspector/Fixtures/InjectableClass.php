<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector\Fixtures;

use Interop\Container\ContainerInterface;
use Slick\Di\DependencyInspector\Parameter;

/**
 * InjectableClass: a test class for auto-hire feature on DI
 *
 * @package Slick\Tests\Di\DependencyInspector\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class InjectableClass
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Parameter
     */
    private $parameter;

    /**
     * @inject Interop\Container\ContainerInterface
     * @var ContainerInterface
     */
    private $bar;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $name = '', $options = [])
    {
        $this->container = $container;
    }

    /**
     * @param Parameter $parameter
     * @return $this
     */
    public function setObject(Parameter $parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function setSomeThing($thing)
    {

    }

    /**
     * @param Parameter $parameter
     * @return $this
     */
    public function addObject(Parameter $parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function setWithDefault($parameter = null)
    {

    }

    /**
     * @ignoreInject
     * @param Parameter $parameter
     */
    public function setIgnoredAnnotation(Parameter $parameter)
    {

    }

    public function injectIt($parameter = null)
    {

    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getBar()
    {
        return $this->bar;
    }
}