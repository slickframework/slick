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