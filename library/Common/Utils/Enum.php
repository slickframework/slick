<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils;

use MyCLabs\Enum\Enum as MyCLabsEnum;

/**
 * An enumeration utility class
 *
 * @package Slick\Common\Utils
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Enum extends MyCLabsEnum
{

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE()
     * given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        $camelCased = strtoupper(Text::camelCaseToSeparator($name, '_'));
        return parent::__callStatic($camelCased, $arguments);
    }
}