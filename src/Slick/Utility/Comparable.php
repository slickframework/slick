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
     * Compares this object with the specified object for order. 
     * 
     * @param object $object The object to compare with
     * 
     * @return integer A negative integer, zero, or a positive integer as this
     *  object is less than, equal to, or greater than the specified object.
     */
    public function compareTo($object);
}