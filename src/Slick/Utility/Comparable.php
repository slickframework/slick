<?php

/**
 * Comparable interface
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

/**
 * Comparable interface
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface Comparable
{

    /**
     * Compares current object with the provided object.
     * 
     * @param object  $compare The object to compare with
     * 
     * @return boolean True if the current object is equals to the provided one
     */
    function compare(self $compare);
}