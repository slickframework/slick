<?php

/**
 * AbstractCollection
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

use Slick\Common\Base;

/**
 * AbstractCollection
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
class AbstractCollection extends Base
{
    /**
     * @var integer Record position
     */
    protected $_position = 0;
    
    /**
     * @readwrite
     * @var array The elements of this collection
     */
    protected $_elements = array();

    /**
     * Adds an element to the collection
     * 
     * @param mixed|object $object The elemente to add to the collection
     * 
     * @return \Slick\Utility\Collection A self instance for method call chains
     */
    public function add($object)
    {
        array_push($this->_elements, $object);
        return $this;
    }

    /**
     *  Adds all of the elements in the specified collection to this collection
     *  
     * @param SlickUtilityCollectionsAbstractCollection $collection
     *
     * @return \Slick\Utility\Collection A self instance for method call chains
     */
    public function addAll(
        \Slick\Utility\Collections\AbstractCollection $collection)
    {
        $elements = $collection->getElements();
        foreach ($elements as $object) {
            $this->add($object);
        }
        return $this;
    }

    /**
     * Removes all of the elements from this collection
     *  
     * @return \Slick\Utility\Collection A self instance for method call chains
     */
    public function clear()
    {        
        $this->_elements = array();
        $this->_position = 0;
        return $this;
    }

    /**
     * Returns true if this collection contains no elements.
     * 
     * @return boolean True if this collection contains no elements, False
     *   otherwise.
     */
    public function isEmpty()
    {
        return empty($this->_elements);
    }

    /**
     * Returns true if this collection contains the specified element.
     * 
     * @param  mixed|object $object The object to search for.
     * 
     * @return boolean True if this collection contains the specified element
     *  or False if not.
     */
    public function contains($object)
    {
        foreach ($this->_elements as $element) {
            if ($element == $object) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if this collection contains all of the elements in the
     * specified collection.
     * 
     * @param  \Slick\Utility\Collections\AbstractCollection $collection 
     * 
     * @return boolean
     */
    public function containsAll(
        \Slick\Utility\Collections\AbstractCollection $collection)
    {
        $items = $collection->getElements();
        foreach ($items as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Removes a single instance of the specified element from this collection,
     * if it is present.
     * 
     * @param  mixed|object $object Element to be removed from this collection
     * 
     * @return boolean true if an element was removed as a result of this call
     */
    public function remove($object)
    {
        $elements = $this->getElements();
        foreach ($elements as $key => $value) {
            if ($value == $object) {
                unset($this->_elements[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * Removes all of this collection's elements that are also contained in
     * the specified collection.
     * 
     * @param  Slick\Utility\Collections\AbstractCollection $collection
     * 
     * @return boolean True if this collection changed as a result of the call
     *   false if no element has been removed.
     */
    public function removeAll(
        \Slick\Utility\Collections\AbstractCollection $collection)
    {
        $return = false;
        $elements = $collection->getElements();
        foreach ($elements as $element) {
            if ($this->remove($element)) {
                $return = true;
            }
        }
        return $return;
    }

    /**
     * Retains only the elements in this collection that are contained in the
     * specified collection.
     * 
     * @param  \Slick\Utility\Collections\AbstractCollection $collection
     * 
     * @return boolean True if this collection changed as a result of the call
     *   false if no element has been removed.
     */
    public function retainAll(
        \Slick\Utility\Collections\AbstractCollection $collection)
    {
        $elements = $this->getElements();
        $changed = false;
        foreach ($elements as $key => $element) {
            if (!$collection->contains($element)) {
                unset($this->_elements[$key]);
                $changed = true;
            }
        }
        return $changed;
    }
}