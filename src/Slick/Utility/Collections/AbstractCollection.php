<?php

/**
 * AbstactCollection
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

use Slick\Common\Base,
    Slick\Common\Exception;

/**
 * This class provides a skeletal implementation of the Collection interface
 * 
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @see  \Slick\Utility\Collections\Collection
 *
 * @property-read integer position Internal list position
 * @property array elements The collection array
 * @method {integer} getPosition() getPosition() Returns the current
 *   collection position elemet.
 * @method {array} getElements() getElements() Return the current collection
 *   array of elements.
 */
abstract class AbstractCollection extends Base Implements
    Collection, \Countable, \Serializable
{

    /**
     * @read
     * @var integer The interal collection pointer
     */
    protected $_position = 0;

    /**
     * @readwrite
     * @var array The internal collection
     */
    protected $_elements = array();

    /**
     * Uses the default iterator implementaion methods
     */
    use Common\IteratorMethods;

    /**
     * Use the default countable implementation method
     */
    use Common\CountableMethods;

    /**
     * Use the slick implementation for serializable interface
     */
    use Common\SerializableMethods;

    /**
     * Sets the internal list of elements.
     * 
     * @param array|\Iterator $elements An array or collection of elements
     *   to set this collection data.
     */
    public function setElements($elements)
    {
        if (!is_array($elements) && !is_a($elements, '\Iterator')) {
            throw new Exception\InvalidArgumentException(
                "Setting elements of a collection is only possible if ".
                "the provided argument is an array or an Iterator"
            );
        }

        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Adds an element to the collection
     * 
     * @param mixed|object $element The elemente to add to the collection
     * 
     * @return boolean True if collection has change as a result of the call
     */
    public function add($element)
    {
        $this->_elements[] = $element;
        return true;
    }

    /**
     * Adds all of the elements in the specified collection to this collection
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be added to this collection.
     *
     * @return boolean True if collection has change as a result of the call
     */
    public function addAll(\Slick\Utility\Collections\Collection $collection)
    {
        foreach ($collection as $element) {
            $this->_elements[] = $element;
        }
        return true;
    }

    /**
     * Returns true if this collection contains the specified element
     *
     * If element implements Slick\Comparable interface
     * Slick\Comparable::compare() will be used to check if element
     * exists in this collection.
     * If its not a Slick\Comparable a regula "==" comparation will
     * be performed to check element existance.
     * 
     * @param mixed|object $element Element whose presence in this collection
     *   is to be tested
     *   
     * @return boolean True if this collection contains the specified element
     */
    public function contains($element)
    {
        foreach ($this->_elements as $item) {
            if (is_a($element, 'Slick\Common\Comparable')) {
                if ($element->compare($item))
                    return true;
            } else if ($element == $item) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if this collection contains all of the elements in the
     * specified collection.
     *
     * If element implements Slick\Comparable interface
     * Slick\Comparable::compare() will be used to check if element
     * exists in this collection.
     * If its not a Slick\Comparable a regula "==" comparation will
     * be performed to check element existance.
     * 
     * @param \Slick\Utility\Collections\Collection $collection collection
     *   to be checked for containment in this collection
     *   
     * @return boolean true if this collection contains all of the elements in
     *   the specified collection
     */
    public function containsAll(
        \Slick\Utility\Collections\Collection $collection)
    {
        foreach ($collection as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Removes a single instance of the specified element from this
     * collection, if it is present
     *
     * If element implements Slick\Comparable interface
     * Slick\Comparable::compare() will be used to check if element
     * exists in this collection.
     * If its not a Slick\Comparable a regula "==" comparation will
     * be performed to check element existance.
     * 
     * @param mixed|object $element Element to be removed from this
     *   collection, if present
     *   
     * @return boolean True if collection has change as a result of the call
     */
    public function remove($element)
    {
        $changed = false;
        foreach ($this->_elements as $key => $item) {
            if (is_a($element, 'Slick\Common\Comparable')) {
                if ($element->compare($element)) {
                    unset($this->_elements[$key]);
                    $changed = true;
                }
            } else if ($element == $item) {
                unset($this->_elements[$key]);
                $changed = true;
            }
        }
        return $changed;
    }

    /**
     * Removes all of this collection's elements that are also contained in
     * the specified collection
     *
     * If element implements Slick\Comparable interface
     * Slick\Comparable::compare() will be used to check if element
     * exists in this collection.
     * If its not a Slick\Comparable a regula "==" comparation will
     * be performed to check element existance.
     * 
     * @param \Slick\Utility\Collections\Collection $collection Collection
     *   containing elements to be removed from this collection.
     *   
     * @return boolean True if this collection changed as a result of the call
     */
    public function removeAll(\Slick\Utility\Collections\Collection $collection)
    {
        $changed = false;
        foreach ($collection as $element) {
            if ($this->remove($element)) {
                $changed = true;
            }
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
    public function retainAll(\Slick\Utility\Collections\Collection $collection)
    {
        $changed = false;
        foreach ($this->_elements as $key => $item) {
            if (!$collection->contains($item)) {
                unset($this->_elements[$key]);
                $changed = true;
            }
        }
        return $changed;
    }

    /**
     * Returns the number of elements in this collection
     * 
     * @return integer The number of elements in this collection
     */
    public function size()
    {
        return sizeof($this->_elements);
    }

    /**
     * Removes all of the elements from this collection
     * 
     * The collection will be empty after this method returns.
     */
    public function clear()
    {
        $this->_elements = array();
    }
    
}