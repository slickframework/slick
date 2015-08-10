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
     * English singular form rules
     *
     * @var array
     */
    private static $singular = array(
        '(matr)ices$' => "\\1ix",
        '(vert|ind)ices$' => "\\1ex",
        '^(ox)en' => "\\1",
        '(alias)es$' => "\\1",
        '([octop|vir])i$' => "\\us",
        '(cris|ax|test)es$' => "\\1is",
        '(shoe)s$' => "\\1",
        '(o)es$' => "\\1",
        '(bus|campus)es$' => "\\1",
        '([m|l])ice$' => "\\1ouse",
        '(x|ch|ss|sh)es$' => "\\1",
        '(m)ovies$' => "\\1\\2ovie",
        '(s)eries$' => "\\1\\2eries",
        '([^aeiouy]|qu)ies$' => "\\1y",
        '([lr])ves$' => "\\1f",
        '(tive)s$'=> "\\1",
        '(hive)s$'=> "\\1",
        '([^f])ves$' => "\\1fe",
        '(^analy)ses$' => "\\sis",
        '((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$'
        => "\\1\\2sis",
        '([ti])a$' => "\\1um",
        '(p)eople$' => "\\1\\2erson",
        '(m)en$' => "\\1an" ,
        '(s)tatuses$' => "\\1\\2tatus",
        '(c)hildren$' => "\\1\\2hild",
        '(n)ews$' => "\\1\\2ews",
        '([^u])s$' => "\\1"
    );

    /**
     * English plural form rules
     *
     * @var array
     */
    private static $plural = array(
        '^(ox)$' => "\\1\\2en",
        '([m|l])ouse$' => "\\1ice",
        '(matr|vert|ind)ix|ex$' => "\\1ices",
        '(x|ch|ss|sh)$' => "\\1es",
        '([^aeiouy]|qu)y$' => "\\1ies",
        '(hive)$' => "\\1s",
        "(?:([^f])fe|([lr])f)$" => "\\1\\2ves",
        'sis$' => "ses",
        '([ti])um$' => "\\1a",
        '(p)erson$' => "\\1eople",
        '(m)an$' => "\\1en",
        '(c)hild$' => "\\1hildren",
        '(buffal|tomat)o$' => "\\1\\2oes",
        '(bu|campu)s$' => "\\1\\2ses",
        '(alias|status|virus)' => "\\1es",
        '(octop)us$' => "\\1i",
        '(ax|cris|test)is' => "\\1es",
        's$' => 's',
        '$' => 's'
    );

    /**
     * Returns the singular version of the given string.
     *
     * @param string $string Singular string to evaluate.
     *
     * @return string The singular version of the provided string.
     */
    public static function singular($string)
    {
        $result = $string;
        foreach (self::$singular as $rule => $replacement) {
            $rule = self::normalize($rule);
            if (preg_match($rule, $string)) {
                $result = preg_replace($rule, $replacement, $string);
                break;
            }
        }
        return $result;
    }

    /**
     * Returns the plural version of the given string.
     *
     * @param string $string Plural string to evaluate.
     *
     * @return string The plural version of the provided string.
     */
    public static function plural($string)
    {
        $result = $string;
        foreach (self::$plural as $rule => $replacement) {
            $rule = self::normalize($rule);
            if (preg_match($rule, $string)) {
                $result = preg_replace($rule, $replacement, $string);
                break;
            }
        }
        return $result;
    }

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