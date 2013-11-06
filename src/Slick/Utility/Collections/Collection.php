<?php

/**
 * Collection
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

/**
 * Collection handles collections of objects.
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class Collection extends AbstractCollection implements
    \Countable, \Serializable, \Iterator
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
     * Serializes the collection object
     * 
     * @return string String representation of this collection
     */
    public function serialize()
    {
        return serialize($this->_elements);
    }

    /**
     * Unserializes the provided string to a collection
     * 
     * @param  string $serialized String representation of a collection
     * 
     * @return \Slick\Utility\Collections\Collection A collection object
     */
    public function unserialize($serialized)
    {
        parent::__construct(array());
        $this->_elements = unserialize($serialized);
    }

    /**
     * Rewind the Iterator to the first element
     * 
     * @return \Slick\Utility\Collections\Collection A collection instance
     *  for method call chains.
     */
    public function rewind()
    {
        $this->_position = 0;
        return $this;
    }

    /**
     * Return the current element
     * 
     * @return mixed|null The element at current position or null if its out
     *  of index
     */
    public function current()
    {
        if (!$this->valid()) {
            return null;
        }
        return $this->_elements[$this->_position];
    }

    /**
     * Return the key of the current element
     * 
     * @return int The current elemten key
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * Move forward to next element
     * 
     * @return \Slick\Utility\Collections\Collection A collection instance
     *  for method call chains.
     */
    public function next()
    {
        ++$this->_position;
        return $this;
    }

    /**
     * Checks if current position is valid.
     * 
     * @return boolean True if the current position has an element,
     *   False it current position has not an element.
     */
    public function valid()
    {
        return isset($this->_elements[$this->_position]);
    }

    
}