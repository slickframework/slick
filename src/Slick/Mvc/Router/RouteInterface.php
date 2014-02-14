<?php

/**
 * RouteInterface
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Router;

/**
 * RouteInterface
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface RouteInterface
{

    /**
     * Returns the route pattern string
     *
     * @return string
     */
    public function getPattern();

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
    public function matches($url);
}