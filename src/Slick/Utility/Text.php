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
     * @param integer $limit If specified, then only substrings up to limit
     *  are returned with the rest of the string being placed in the
     *  last substring.
     *  
     * @return array Returns an array containing substrings of subject
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

