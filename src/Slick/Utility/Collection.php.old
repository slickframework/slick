<?php

/**
 * Collection
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

use Slick\Common\Base;

/**
 * Collection handles collections of objects.
 *
 * Objects from this class can be used as an iterator or an array.
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Collection extends Base implements
    \Iterator, \Countable, \Serializable, \arrayaccess
{

    /**
     * @var integer Record position
     */
    protected $_position = 0;

    /**
     * @write
     * @var array The list of records of this list
     */
    protected $_records = array();

    /**
     * 
     * @readWrite
     * @var array General options.
     */
    protected $_options;

    /**
     * Takes the pointer back to the beginning of the list
     * 
     * Restarts the iteration.
     *
     * @return \Slick\Utility\Collention The collection object for
     *   method call chain.
     */
    public function rewind()
    {
        $this->_position = 0;
        return $this;
    }

    /**
     * Takes the pointer to the end of the list.
     * 
     * @return \Slick\Utility\Collention The collection object for
     *   method call chain.
     */
    public function forward()
    {
        $this->_position = $this->count() -1;
        return $this;
    }

    /**
     * Returns the value at the current position of the list.
     *
     * @return mixed|Object The current record
     */
    public function current() 
    {
        return $this->_records[$this->_position];
    }

    /**
     * This should return the current value of the pointer.
     *
     * @return integer Pointer index
     */
    public function key() 
    {
        return $this->_position;
    }

    /**
     * Moves the pointer to the next value in the list.
     *
     * @return \Slick\Utility\Collention The collection object for
     *   method call chain.
     */
    public function next()
    {
        ++$this->_position;
        return $this;
    }

    /**
     * Moves the pointer to the next value in the list.
     *
     * @return \Slick\Utility\Collention The collection object for
     *   method call chain.
     */
    public function previous()
    {
        --$this->_position;
        return $this;
    }

    /**
     * Returns a boolean indicating if the there is data at
     * the current position in the list.
     *
     * @return boolean True if data exists at current poiter
     *  positions, False otherwise.
     */
    public function valid()
    {
        return isset($this->_records[$this->_position]);
    }

    /**
     * Returns the total recored in the list.
     *
     * @return int The number of items in the list
     */
    public function count()
    {
        return count($this->_records);
    }

    /**
     * Serializes current list array.
     *
     * @return string The serialized version of the list array.
     */
    public function serialize()
    {
        return serialize($this->_records);
    }

    /**
     * Recovers from serialization
     *
     * @param string $data The serialization data to restore
     */
    public function unserialize($data)
    {
        $this->_records = unserialize($data);
        parent::__construct(array());
    }

    /**
     * Adds an object to the list.
     *
     * @param mixed $object The object to add
     */
    public function add($object)
    {
        $this->_records[] = $object;
    }

    /**
     * Removes the element whit the provided key from the collection.
     * 
     * If no key is provided, the element from the current position
     * will be removed.
     * 
     * @param integer $key The key element to remove.
     * 
     * @return \Slick\Utility\Collention The collection object for
     *   method call chain.
     */
    public function remove($key = -1)
    {
        if ($key < 0) {
            $key = $this->_position--;
            if ($this->_position < 0) {
                $this->_position = 0;
            }
        }
        unset($this->_records[$key]);
        return $this;
    }

    /**
     * Checks if the list is empty
     * 
     * @return boolean True if list has no elements, false otherwise.
     */
    public function isEmpty()
    {
        return ($this->count() == 0);
    }

    /**
     * Implementing offset assigning for use objbect as an array
     *
     * @param integer      $offset The offset position for the value.
     * @param mixed|object $value  The object to add
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_records[] = $value;
        } else {
            $this->_records[$offset] = $value;
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
        return isset($this->_records[$offset]);
    }

    /**
     * Removes an element for a give offset.
     *
     * @param integer $offset The offset index to remove
     */
    public function offsetUnset($offset)
    {
        unset($this->_records[$offset]);
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
        return isset($this->_records[$offset]) ?
            $this->_records[$offset] :
            null;
    }

    /**
     * Returns the last object in the list
     * 
     * @return mixed|object The object in the last position
     */
    public function last()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->_records[$this->count() -1];
    }

    /**
     * Returns the first object from list
     * 
     * @return mixed|object The object in the first position
     */
    public function first()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->_records[0];
    }
}