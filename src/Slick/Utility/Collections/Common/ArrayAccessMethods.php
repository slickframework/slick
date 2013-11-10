<?php

/**
 * ArrayAccessMethods
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections\Common;

/**
 * ArrayAccess Methods for classes implementing SPL ArrayAccess interface.
 *
 * The class that uses this trait must extend Slick\Common\Base and need to
 * define a protected property $_elements as an array.
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait ArrayAccessMethods
{

	/**
     * Implementing offset assigning for use objbect as an array
     *
     * @param integer      $offset The offset position for the value.
     * @param mixed|object $value  The object to add
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_elements[] = $value;
        } else {
            $this->_elements[$offset] = $value;
        }
    }

    /**
     * Check if an existing offset exists in the list
     *
     * @param integer $offset The offset index to check
     * 
     * @return boolean True if the offset exists in the list, 
     *   False otherwise
     */
    public function offsetExists($offset)
    {
        return isset($this->_elements[$offset]);
    }

    /**
     * Removes an element for a give offset.
     *
     * @param integer $offset The offset index to remove
     */
    public function offsetUnset($offset)
    {
        unset($this->_elements[$offset]);
    }

    /**
     * Returns the object in the provided offset index.
     *
     * @param integer $offset The offset index to retrieve
     * 
     * @return mixed|object The object for a given position
     */
    public function offsetGet($offset)
    {
        return isset($this->_elements[$offset]) ?
            $this->_elements[$offset] :
            null;
    }
}