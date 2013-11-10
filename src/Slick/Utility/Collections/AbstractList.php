<?php

/**
 * Abstract List
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

use Slick\Utility\Exception;

/**
 * AbstractList class provides a skeletal implementation of the List interface
 */
abstract class AbstractList extends AbstractCollection implements ListInterface
{

    /**
     * Inserts the specified element at the specified position in this list
     * 
     * @param mixed|object $element Element to be inserted
     * @param integer      $index   Index at which the specified element is
     *   to be inserted
     *
     * @return boolean True if collection has change as a result of the call
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function add($element, $index = null)
    {
        $this->_checkIndex($index);

        if (!is_null($index)) {
            $left = array_slice($this->_elements, 0, $index);
            $right = array_slice($this->_elements, $index);
            $this->_elements = $left;
            $this->_elements[] =$element;
            foreach ($right as $shifted) {
                $this->_elements[] = $shifted;
            }
        } else {
            $this->_elements[] = $element;
        }
        return true;
    }

    /**
     * Inserts all of the elements in the specified collection into this list
     * at the specified position
     * 
     * @param \Slick\Utility\Collections\Collection $collection A collection
     *   containing elements to be added to this list
     * @param integer                               $index      Index at which
     *   to insert the first element from the specified collection
     *
     * @return boolean True if collection has change as a result of the call
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function addAll(
        \Slick\Utility\Collections\Collection $collection, $index = null)
    {
        $this->_checkIndex($index);

        if (!is_null($index)) {
            $left = array_slice($this->_elements, 0, $index);
            $right = array_slice($this->_elements, $index);
            $this->_elements = $left;
            parent::addAll($collection);
            foreach ($right as $shifted) {
                $this->_elements[] = $shifted;
            }
        } else {
            parent::addAll($collection);
        }
        return true;
    }

    /**
     * Returns the element at the specified position in this list.
     * 
     * @param integer $index Index of the element to return
     * 
     * @return mixed|object the element at the specified position in this list
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function get($index)
    {
        $this->_checkIndex($index);

        return $this->_elements[$index];
    }

    /**
     * Replaces the element at the specified position in this list with the
     * specified element
     * 
     * @param mixed|object $element Element to be stored at the
     *  specified position
     * @param integer      $index   Index of the element to replace
     *
     * @return mixed|Object the element previously at the specified position
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function set($element, $index)
    {
        $this->_checkIndex($index);

        $old = $this->_elements[$index];
        $this->_elements[$index] = $element;
        return $old;
    }

    /**
     * Returns the index of the first occurrence of the specified element in
     * this list, or -1
     * 
     * @param mixed|object $element Element to search for
     * @param boolean      $last Set it to true to return the last occurrence
     *  of the especified element.
     * 
     * @return integer the index of the first occurrence of the specified
     *  element in this list, or -1 if this list does not contain the element
     */
    public function indexOf($element, $last = false)
    {
        $indx = -1;
        foreach ($this->_elements as $index => $item) {
            if (is_a($element, '\Slick\Common\Base')) {
                if ($element->equals($item)) {
                    if (!$last)
                        return $index;
                    $indx = $index;
                }
            } else if ($element == $item) {
                if (!$last)
                    return $index;
                $indx = $index;
            }
        }
        return $indx;
    }

    /**
     * Removes a single instance of the specified element from this
     * collection, if it is present
     *
     * If element is a Slick\Common\Base, Slick\Common\Base::equals()
     * will be used to check if element exists in this collection.
     * If its not a Slick\Common\Base a regula "==" comparation will
     * be performed to check element existance.
     *
     * @see Slick\Common\Base::equals()
     * 
     * @param mixed|object $element Element to be removed from this
     *   collection, if present
     *   
     * @return boolean True if collection has change as a result of the call
     */
    public function remove($element)
    {
        $changed = parent::remove($element);
        if ($changed) {
            $this->_elements = array_values($this->_elements);
        }
        return $changed;
    }

    /**
     * Removes all of this collection's elements that are also contained in
     * the specified collection
     *
     * If element is a Slick\Common\Base, Slick\Common\Base::equals()
     * will be used to check if element exists in this collection.
     * If its not a Slick\Common\Base a regula "==" comparation will
     * be performed to check element existance.
     *
     * @see Slick\Common\Base::equals()
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be removed from this collection.
     *   
     * @return boolean True if this collection changed as a result of the call
     */
    public function removeAll(\Slick\Utility\Collections\Collection $collection)
    {
        $changed = parent::removeAll($collection);
        if ($changed) {
            $this->_elements = array_values($this->_elements);
        }
        return $changed;
    }

    /**
     * Retains only the elements in this collection that are contained in the
     * specified collection
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be retained in this collection
     *   
     * @return boolean True if this collection changed as a result of the call
     */
    public function retainAll(
        \Slick\Utility\Collections\Collection $collection)
    {
        $changed = parent::retainAll($collection);
        if ($changed) {
            $this->_elements = array_values($this->_elements);
        }
        return $changed;
    }

    /**
     * Returns the sequence of elements from the list as specified by
     * the index and length parameters.
     * 
     * @param integer $index  Index of the first position in the list
     * @param integer $length If length is given and is positive, then the
     *  sequence will have up to that many elements in it.
     * 
     * @return \Slick\Utility\Common\AbstractList A sub list of this list
     *
     * @throws \Slick\Utility\Exception\IndexOutOfBoundsException If the index
     *   is out of range (index < 0 || index >= size())
     * @throws \Slick\Utility\Exception\InvalidArgumentException If index is
     *   not a numeric value.
     */
    public function subList($index = 0, $length = null)
    {
        $this->_checkIndex($index);

        $class = get_class($this);
        $subList = array_slice($this->_elements, $index, $length);
        return new $class(array('elements' => $subList));
    }

    /**
     * Checks if index is valid
     * 
     * @param integer $index The position index to check
     */
    protected function _checkIndex($index)
    {
        // Index isn't null or 0 based integer
        if (!is_null($index) && !is_integer($index)) {
            throw new Exception\InvalidArgumentException(
                "Index must be a valid, zero base integer or null."
            );
        }

        // Index is out of list bounds
        if (is_numeric($index) && ($index < 0 || $index >= $this->size())) {
            throw new Exception\IndexOutOfBoundsException(
                "The index '{$index}' is out of this list bounds."
            );
        }
    }
}