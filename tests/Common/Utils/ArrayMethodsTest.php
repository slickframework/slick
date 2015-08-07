<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Utils;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\ArrayMethods;

/**
 * Class ArrayMethodsTest
 *
 * @package Slick\Tests\Common\Utils
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class ArrayMethodsTest extends TestCase
{

    /**
     * Should trim all elements of an array
     * @test
     */
    public function trimArrayElements()
    {
        $expected = array('one', 'two', 'three');
        $dirty = array('one ', ' two ', ' three');
        $this->assertEquals($expected, ArrayMethods::trim($dirty));
    }

    /**
     * Try to clean a dirty array
     *
     * @test
     */
    public function cleanAnArray()
    {
        $expected = array('one', 'two', 'three');
        $dirty = array_merge($expected, array('', ''));
        $this->assertEquals($expected, ArrayMethods::clean($dirty));
    }
}
