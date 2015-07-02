<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils;

/**
 * Utility class for handy array operations.
 *
 * @package Slick\Common\Utils
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ArrayMethods
{

    /**
     * Avoid the creation of an ArrayMethods instance.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // do nothing
    }

    /**
     * Avoid the clonation of an ArrayMethods instance.
     * @codeCoverageIgnore
     */
    private function __clone()
    {
        // do nothing
    }

    /**
     * Trims every element of the provided array.
     *
     * @param array $array The source array with items to trim.
     *
     * @return array A copy of the given array with all items trimmed.
     */
    public static function trim($array)
    {
        return array_map(
            function($item) {
                return trim($item);
            },
            $array
        );
    }
}