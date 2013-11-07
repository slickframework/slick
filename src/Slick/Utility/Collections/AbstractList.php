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
     */
    public function add($element, $index = null)
    {
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
     */
    public function addAll(
        \Slick\Utility\Collections\Collection $collection, $index = null)
    {

    }
}