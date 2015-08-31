<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\Definition;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Di\Definition\DefinitionList;

/**
 * Definition list test case
 *
 * @package Slick\Tests\Di\Definition
 */
class DefinitionListTest extends TestCase
{

    public function testAddOtherObject()
    {
        $this->setExpectedException(
            'Slick\Di\Exception\InvalidArgumentException'
        );
        $list = new DefinitionList();
        $list->offsetSet(2, new \stdClass());
    }
}
