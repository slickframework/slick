<?php

/**
 * CountableMethods
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections\Common;

/**
 * Countable Method for classes implementing SPL Countable interface.
 *
 * The class that uses this trait must extend Slick\Common\Base and need to
 * define a protected property $_elements as an array.
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait CountableMethods
{

    /**
     * Count elements in this collection
     * 
     * @return int The total elements
     */
    public function count()
    {
        return count($this->_elements);
    }

    /**
     * Checks if the current collention is empty
     *
     * @return boolean True if there is no elements in this collection.
     */
    public function isEmpty()
    {
        return empty($this->_elements);
    }
}