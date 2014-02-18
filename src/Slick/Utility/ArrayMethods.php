<?php

/**
 * ArrayMethods
 * 
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Utility;

/**
 * ArrayMethods is an utility class for handy array operations.
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
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
     * Converts a multidimensional array into a uni-dimensional array.
     *
     * @param array $array  The source array to iterate.
     * @param array $return The return values, for recursive proposes.
     *
     * @return array A unidirectional array from source array.
     */
    public static function flatten($array, $return = array())
    {
        foreach ($array as $value) {
            if (is_array($value) || is_object($value)) {
                $return = self::flatten($value, $return);
            } else {
                $return[] = $value;
            }
        }
        return $return;
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
     * Trims every element of the given array.
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
