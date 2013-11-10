<?php

/**
 * ArrayList
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

use Slick\Utility\Collections\AbstractList,
	Slick\Utility\Collections\Common\ArrayAccessMethods;

/**
 * ArrayList is a resizable-array implementation of the List interface.
 *
 * Objects from this class can be used as normal arrays:
 * $array = new ArrayList();
 * $array[] = "value"; // add a new element to the list!
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ArrayList extends AbstractList implements \ArrayAccess
{

	/**
	 * Use trait with default implementation of ArrayAccess interface
	 */
	use ArrayAccessMethods;

	/**
     * Implementing offset assigning for use objbect as an array
     *
     * @param integer      $offset The offset position for the value.
     * @param mixed|object $value  The object to add
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the offset
     *   is out of range (offset < 0 || offset >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If offset is
     *   not a numeric value.
     */
    public function offsetSet($offset, $value)
    {
    	$this->_checkIndex($offset);

        if (is_null($offset)) {
            $this->_elements[] = $value;
        } else {
            $this->_elements[$offset] = $value;
        }
    }

    /**
     * Removes an element for a give offset.
     *
     * @param integer $offset The offset index to remove
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the offset
     *   is out of range (offset < 0 || offset >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If offset is
     *   not a numeric value.
     */
    public function offsetUnset($offset)
    {
    	$this->_checkIndex($offset);

        unset($this->_elements[$offset]);
        $this->_elements = array_values($this->_elements);
    }
}
