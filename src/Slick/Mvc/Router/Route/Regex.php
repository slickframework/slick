<?php

/**
 * Regex
 *
 * @package   Slick\Mvc\Router\Route
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Router\Route;
use Slick\Mvc\Router\AbstractRoute;
use Slick\Mvc\Router\RouteInterface;
use Slick\Utility\ArrayMethods;

/**
 * Regex
 *
 * @package   Slick\Mvc\Router\Route
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Regex extends AbstractRoute implements RouteInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $_keys;

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

        //check values
        preg_match_all("#^{$pattern}$#", $url, $values);

        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            //values found, modify parameters and return
            unset($values[0]);
            $derived = array_combine($this->_keys, ArrayMethods::flatten($values));
            $this->setParams(array_merge($this->getParams(), $derived));
            return true;
        }

        return false;
    }
}