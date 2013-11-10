<?php

/**
 * List interface
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

/**
 * ListInterface - An ordered collection (also known as a sequence)
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ListInterface extends Collection
{

    /**
     * Inserts the specified element at the specified position in this list
     * 
     * @param mixed|object $element Element to be inserted
     * @param integer      $index   Index at which the specified element is
     *   to be inserted
     *
     * @return boolean True if collection has change as a result of the call
     */
    public function add($element, $index = null);

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
     */
    public function addAll(
        \Slick\Utility\Collections\Collection $collection, $index = null);

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
    public function get($index);

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
    public function set($element, $index);

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
    public function indexOf($element, $last = false);
}