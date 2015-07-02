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
     * PCRE Unicode support flag
     * @var boolean
     */
    public static $hasPcreUnicodeSupport;

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
     * Is PCRE compiled with Unicode support?
     *
     * @return bool
     */
    public static function hasPcreUnicodeSupport()
    {
        if (static::$hasPcreUnicodeSupport === null) {
            static::$hasPcreUnicodeSupport =
                defined('PREG_BAD_UTF8_OFFSET_ERROR') &&
                @preg_match('/\pL/u', 'a') == 1;
        }
        return static::$hasPcreUnicodeSupport;
    }
    /**
     * Converts camel case strings to words separated by provided string
     *
     * @param string $text The text to evaluate
     * @param string $sep  The separator (or glue) for the words
     *
     * @return string
     */
    public static function camelCaseToSeparator($text, $sep = " ")
    {
        if (!is_scalar($text) && !is_array($text)) {
            return $text;
        }
        if (static::hasPcreUnicodeSupport()) {
            $pattern = array(
                '#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#',
                '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'
            );
            $replacement = array($sep . '\1', $sep . '\1');
        } else {
            $pattern = array(
                '#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#',
                '#(?<=(?:[a-z0-9]))([A-Z])#');
            $replacement = array('\1' . $sep . '\2', $sep . '\1');
        }
        return preg_replace($pattern, $replacement, $text);
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
        return self::$delimiter.trim($pattern, self::$delimiter)
        . self::$delimiter;
    }
}