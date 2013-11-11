<?php

/**
 * ArraySet
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

use Slick\Utility\Collctions\AbstractSet,
	Slick\Utility\Collections\Common\ArrayAccessMethods;


class ArraySet extends AbstractSet implements \ArrayAccess
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
            $this->add($value);
        } else {
            $this->_elements[$offset] = $value;
        }
    }

}