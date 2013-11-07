<?php

/**
 * Collection interface
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

/**
 * Collection interface
 */
interface Collection extends \Iterator
{

    /**
     * Adds an element to the collection
     * 
     * @param mixed|object $element The elemente to add to the collection
     * 
     * @return boolean True if collection has change as a result of the call
     */
    public function add($element);

    /**
     * Adds all of the elements in the specified collection to this collection
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be added to this collection.
     *
     * @return boolean True if collection has change as a result of the call
     */
    public function addAll(\Slick\Utility\Collections\Collection $collection);

    /**
     * Returns true if this collection contains the specified element
     * 
     * @param mixed|object $element Element whose presence in this collection
     *   is to be tested
     *   
     * @return boolean True if this collection contains the specified element
     */
    public function contains($element);

    /**
     * Returns true if this collection contains all of the elements in the
     * specified collection.
     * 
     * @param \Slick\Utility\Collections\Collection $collection collection
     *   to be checked for containment in this collection
     *   
     * @return boolean true if this collection contains all of the elements in
     *   the specified collection
     */
    public function containsAll(
        \Slick\Utility\Collections\Collection $collection);

    /**
     * Removes a single instance of the specified element from this
     * collection, if it is present
     * 
     * @param mixed|object $element Element to be removed from this
     *   collection, if present
     *   
     * @return boolean True if collection has change as a result of the call
     */
    public function remove($element);

    /**
     * Removes all of this collection's elements that are also contained in
     * the specified collection
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be removed from this collection.
     *   
     * @return boolean True if this collection changed as a result of the call
     */
    public function removeAll(\Slick\Utility\Collections\Collection $collection);

    /**
     * Retains only the elements in this collection that are contained in the
     * specified collection
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be retained in this collection
     *   
     * @return boolean True if this collection changed as a result of the call
     */
    public function retainAll(\Slick\Utility\Collections\Collection $collection);

    /**
     * Returns the number of elements in this collection
     * 
     * @return integer The number of elements in this collection
     */
    public function size();
}