<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\Definition;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Definition\Factory;

/**
 * Factory definition test case
 * @package Slick\Tests\Di\Definition
 */
class FactoryTest extends TestCase
{

    public function testCallbackExecution()
    {
        $definition = (new Factory(['name' => 'callbackTest']))
            ->setCallable($this->getCallback(), ['test']);
        $obj = $definition->resolve();
        $this->assertEquals('test', $obj->value);
    }

    private function getCallback()
    {
        $callback = function($value) {
            $obj = new \stdClass();
            $obj->value = $value;
            return $obj;
        };
        return $callback;
    }
}
