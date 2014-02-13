<?php

/**
 * Text
 * 
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Utility;

/**
 * Text is an utility class for handy string operations.
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class Text
{
    /**
     * Avoid the creation of an Text instance.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // do nothing
    }


    /**
     * Avoid the clonation of an Text instance.
     * @codeCoverageIgnore
     */
    private function __clone()
    {
        // do nothing
    }

    /**
     * @var char Pattern delimiter character
     */
    private static $_delimiter = '/';

    /**
     * English singular form rules
     *
     * @var array
     */
    private static $_singular = array(
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
    private static $_plural = array(
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
        foreach (self::$_singular as $rule => $replacement) {
            $rule = self::_normalize($rule);
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
        foreach (self::$_plural as $rule => $replacement) {
            $rule = self::_normalize($rule);
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
            self::_normalize($pattern),
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
     * Split string by a regular expression.
     *
     * This is a less formal method then the PHP preg_split() function.
     *
     * @param string  $string The string to split.
     * @param string  $pattern The regular expression for split operation.
     * @param integer $limit If specified, then only substring up to limit
     *  are returned with the rest of the string being placed in the
     *  last substring.
     *  
     * @return array Returns an array containing substring of subject
     *  split along boundaries matched by pattern.
     */
    public static function split($string, $pattern, $limit = null)
    {
        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        return preg_split(self::_normalize($pattern), $string, $limit, $flags);
    }
    
    /**
     * Normalize the given pattern
     * 
     * This allows the  remaining methods can operate on a pattern without
     * first having to check it or normalize it.
     *
     * @param string $pattern The pattern string to normalize.
     * 
     * @return string A normalized pattern string.
     */
    private static function _normalize($pattern)
    {
        return self::$_delimiter . trim($pattern, self::$_delimiter)
            . self::$_delimiter;
    }
}   

