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
     * Returns a copy of the given array without empty items.
     *
     * @param array $array The data array with empty items to clean.
     *
     * @return array A copy of given array without empty items.
     */
    public static function clean($array)
    {
        return array_filter(
            $array,
            function($item){
                $item = trim($item);
                return !empty($item);
            }
        );
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

    /**
     * Recursive method to parse dot notation keys and retrieve the value
     *
     * @param string $key     The key/index to search
     * @param mixed  $default The value if key doesn't exists
     * @param array  $data    The data to search
     *
     * @return mixed The stored value or the default value if key
     *               or index was not found.
     */
    public static function getValue($key, $default, $data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);
        if (isset($data[$first])) {
            if (count($parts) > 0) {
                $newKey = implode('.', $parts);
                return static::getValue($newKey, $default, $data[$first]);
            }
            $default = $data[$first];
        }
        return $default;
    }

    /**
     * Recursive method to parse dot notation keys and set the value
     *
     * @param string $key   The key used to store the value in configuration.
     * @param mixed  $value The value to store under the provided key.
     * @param array  $data  The data to search
     */
    public static function setValue($key, $value, &$data)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);
        if (count($parts) > 0) {
            $newKey = implode('.', $parts);
            if (!array_key_exists($first, $data)) {
                $data[$first] = array();
            }
            static::setValue($newKey, $value, $data[$first]);
            return;
        }
        $data[$first] = $value;
    }
}
