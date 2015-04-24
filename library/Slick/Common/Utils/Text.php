<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils;

/**
 * Text is an utility class for handy string operations.
 *
 * @package Slick\Common\Utils
 */
class Text
{

    /**
     * @var string Pattern delimiter character
     */
    private static $delimiter = '/';

    /**
     * Perform a global regular expression match
     *
     * This method is less formal then preg_match_all() function,
     * returning the predictable matches.
     *
     * @param string $string  The string to match against.
     * @param string $pattern The regular expression pattern string.
     *
     * @return array|null An array with matches from the given string. If no
     *  match is found, null will be returned.
     */
    public static function match($string, $pattern)
    {
        $matches = array();
        preg_match_all(
            self::normalize($pattern),
            $string,
            $matches,
            PREG_PATTERN_ORDER
        );
        if (!empty($matches[1])) {
            return $matches[1];
        } else if (!empty($matches[0])) {
            return $matches[0];
        }
        return null;
    }

    /**
     * Normalize the provided pattern
     *
     * This allows the remaining methods to operate on a pattern without
     * first having to check it or normalize it.
     *
     * @param string $pattern The pattern string to normalize.
     *
     * @return string A normalized pattern string.
     */
    private static function normalize($pattern)
    {
        return self::$delimiter . trim($pattern, self::$delimiter)
        . self::$delimiter;
    }
}