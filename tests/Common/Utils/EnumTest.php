<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Utils;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Utils\Enum;

/**
 * Enum test case
 *
 * @package Slick\Tests\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EnumTest extends TestCase
{

    public function testEnumCall()
    {
        $state = State::open();
        $this->assertEquals('open', $state->getValue());
    }
}

/**
 * Class State
 * @package Slick\Tests\Common\Utils
 *
 * @method static State open()
 */
class State extends Enum
{
    const OPEN = 'open';
}