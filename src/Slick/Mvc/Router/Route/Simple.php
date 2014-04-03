<?php

/**
 * Simple Route
 *
 * @package   Slick\Mvc\Router\Route
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Router\Route;

use Slick\Mvc\Router\AbstractRoute,
    Slick\Mvc\Router\RouteInterface,
    Slick\Utility\ArrayMethods;

/**
 * Simple Route
 *
 * @package   Slick\Mvc\Router\Route
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Simple extends AbstractRoute implements RouteInterface
{
    /**
     * Matches the URL against the list of routes
     *
     * Creates the correct regular expression search string and returns
     * any matches to the provided $url.
     *
     * @param string $url The URL string to check.
     *
     * @return boolean True if the url matches, false otherwise.
     */
    public function matches($url)
    {
        $pattern = $this->getPattern();

        //get keys
        preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);

        if (sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1])) {
            $keys = $keys[1];
        } else {
            //no keys in the pattern, return a simple match
            return (boolean) preg_match("#^{$pattern}$#", $url);
        }

        //normalize route pattern
        $pattern = preg_replace(
            "#(:[a-zA-Z0-9]+)#",
            "([a-zA-Z0-9-_]+)",
            $pattern
        );

        //check values
        preg_match_all("#^{$pattern}$#", $url, $values);

        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            //unset the matched url
            unset($values[0]);
            //values found, modify parameters  and return
            $derived = array_combine($keys, ArrayMethods::flatten($values));
            $this->setParams(array_merge($this->getParams(), $derived));
            return true;
        }

        return false;
    }
}