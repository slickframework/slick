<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector\Fixtures;
use Slick\Di\DependencyInspector\Parameter;

/**
 * Property Injectable test class
 *
 * @package Slick\Tests\Di\DependencyInspector\Fixtures
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PropertyInjectable
{

    /**
     * @inject
     * @var \Slick\Di\DependencyInspector\Parameter
     */
    private $foo;

    /**
     * @inject \Slick\Di\DependencyInspector\Parameter
     */
    protected $bar;

    public function getFoo()
    {
        return $this->foo;
    }
}