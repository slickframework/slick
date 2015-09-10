<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\DependencyInspector\Fixtures;

/**
 * MethodFailing
 * @package Slick\Tests\Di\DependencyInspector\Fixtures
 */
class MethodFailing
{

    /**
     * @inject
     * @var string
     */
    protected $foo;

    /**
     * @inject
     * @param $thing
     */
    public function setOtherSomeThing($thing)
    {

    }
}