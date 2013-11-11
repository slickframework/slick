<?php

/**
 * AbstractSet
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections;

/**
 * This class provides a skeletal implementation of the Set interface to
 * minimize the effort required to implement this interface.
 *
 * @package   Slick\Utility\Collections
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSet extends AbstractCollection implements Set
{
	/**
	 * @readwrite
	 * @var boolean Flag for the use of null elements.
	 */
	protected $_allowNull = false;

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

        $this->_elements = array();
        foreach ($elements as $element) {
            $this->add($element) ;
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
    	if (is_null($element) && !$this->getAllowNull()) {
    		throw new Exception\NullPoiterException(
    			"This set doesn't allow null elements."
    		);
    	}

    	if (in_array($element, $this->_elements, true) === false) {
        	$this->_elements[] = $element;
        	return true;
    	}
    	return false;
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
    	$changed = false;
        foreach ($collection as $element) {
            $update = $this->add($element);
        	$changed = $changed ? $changed : $update;
        }
        return $changed;
    }
}