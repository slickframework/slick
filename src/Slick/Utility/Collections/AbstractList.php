<?php

/**
 * AbstractList
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

use Slick\Utility\Exception;

abstract class AbstractList extends Collection implements
    \ArrayAccess
{
    
    /**
     * Appends the specified element to this list.
     *
     * If index is provided, Shifts the element currently at that position
     * (if any) and any subsequent elements to the right (adds one to their
     * indices).
     * If no index is given, the element will be added to the end of the 
     * internal list.
     * 
     * @param mixed|object $element  The object to add
     * @param integer      $index    The index (position) where to insert
     *
     * @return \Slick\Utility\Collections\AbstractList A list instance
     *  for method call chains.
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function add($element, $index = null)
    {
        $this->_checkIndex($index);

        if (!is_null($index)) {
            $left = array_slice($this->_elements, 0, $index);
            $right = array_slice($this->_elements, $index);

            $this->_elements = $left;
            $this->_elements[] = $element;

            foreach ($right as $value) {
                $this->add($value);
            }

        } else {
            $this->_elements[] = $element;
        }
        return $this;
    }

    /**
     * Inserts all of the elements in the specified collection into this list
     *
     * If index is provided, Shifts the element currently at that position
     * (if any) and adds the collection after that positions. All the elements
     * at right will be added on top of it, shifting their indexes the total 
     * elements of added collection.
     * If no index is given, alll the elements will be added to the end of the 
     * internal list.
     * 
     * @param \Slick\Utility\Collections\Collection $col The collection to add
     * @param integer                               $index The position where
     *   to insert the provided collection
     *
     * @return \Slick\Utility\Collections\AbstractList A list instance
     *  for method call chains.
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function addAll(
        \Slick\Utility\Collections\Collection $col, $index = null)
    {
        $this->_checkIndex($index);

        $elements = $col->getElements();

        if (!is_null($index)) {
            $left = array_slice($this->_elements, 0, $index);
            $right = array_slice($this->_elements, $index);
            $this->_elements = $left;

            foreach ($elements as $new) {
                $this->_elements[] = $new;
            }
            
            foreach ($right as $value) {
                $this->_elements[] = $value;
            }
        } else {
            foreach ($elements as $new) {
                $this->_elements[] = $new;
            }
        }
        return $this;
    }

    /**
     * Returns the element at the specified position in this list.
     * 
     * @param integer $index The position where element is in this list
     * 
     * @return mixed|object The object that is at the provided index (position)
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function get($index)
    {
        $this->_checkIndex($index);
        return $this->_elements[$index];
    }

    /**
     * Replaces the element at the given position with the provided element
     * 
     * @param mixed|object $element  The object to add
     * @param integer      $index    The index (position) where to update
     *
     * @return \Slick\Utility\Collections\AbstractList A list instance
     *  for method call chains.
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function set($element, $index)
    {
        $this->_checkIndex($index);
        $this->_elements[$index] = $element;
        return $this;
    }

    /**
     * Returns a view of the portion of this list between the specified index, inclusive,
     * and the length requested.
     * 
     * @param integer $index  The start position of the sublist
     * @param integer $length The amount of elements to retrieve
     * 
     * @return \Slick\Utility\Collections\AbstractList
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function subList($index, $length = null)
    {
        $this->_checkIndex($index);
        $elements = array_slice($this->_elements, $index, $length);
        $class = get_class($this);
        return new $class(array('elements' => $elements));
    }

    /**
     * Implementing offset assigning for use objbect as an array
     *
     * @param integer      $offset The offset position for the value.
     * @param mixed|object $value  The object to add
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->add($value);
        } else {
            $this->set($value, $offset);
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
        if ($this->offsetExists($offset))
            unset($this->_elements[$offset]);
    }

    /**
     * Returns the object in the provided offset index.
     *
     * @param integer $offset The offset index to retrieve
     * 
     * @return mixed|object The object for a given position
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    public function offsetGet($offset)
    {
        $this->_checkIndex($offset);
        return $this->_elements[$offset];
    }

    /**
     * Check the offset index before change the internal list
     * 
     * @param  mixed $index The offset index to check
     *
     * @throws \Slick\Utility\Exception\InvalidArgumentException If the index
     *   is not a zero based positive integer.
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the
     *   index < 0 or the index > list size.
     */
    protected function _checkIndex($index)
    {

        // Index must be a valid positive number
        if (!is_null($index) && !is_int($index)) {
            throw new Exception\InvalidArgumentException(
                "Index must be a zero based positive integer."
            );
        }

        // Index must be withind the list bounds.
        if ($index < 0 || $index >= sizeof($this->_elements)) {
            throw new Exception\IndexOutOfBoundsException(
                "The index '{$index}' is not in between 0 and "
                . sizeof($this->_elements)
            );
            
        }
    }
}