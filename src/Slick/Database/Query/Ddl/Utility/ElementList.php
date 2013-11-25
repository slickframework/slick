<?php

/**
 * ElementList
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

/**
 * ElementList - List of table elements (columns, indexes, foreignKeys)
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ElementList extends \ArrayObject
{
    
    /**
     * Appends a table element to the element list
     * 
     * @param \Slick\Database\Query\Ddl\Utility\TableElementInterface $value
     * 
     * @return boolean True if the list has changed as a result of this call
     */
    public function append(TableElementInterface $value)
    {
        return $this->update(null, $value);
    }

    /**
     * Updates or inserts the element at a given key
     * 
     * @param mixed                                                   $key
     * @param \Slick\Database\Query\Ddl\Utility\TableElementInterface $value
     * 
     * @return boolean True if the list has changed as a result of this call
     */
    public function update($key, TableElementInterface $value)
    {
        if (!$this->contains($value)) {
            $this[$key] = $value;
            return true;
        }
        return false;
    }

    /**
     * Updates or inserts the element at a given offset
     *
     * Overrides the ArrayObject::offsetSet() to force the insertion of
     * TableElementsInterface only.
     * 
     * @param mixed $offset The index being set.
     * @param mixed $value  The new value for the index.
     * 
     * @return boolean True if the list has changed as a result of this call
     */
    public function offsetSet($offset, $value)
    {
        return $this->update($offset, $value);
    }

    /**
     * Checks if the object already exists in the list.
     * 
     * @param \Slick\Database\Query\Ddl\Utility\TableElementInterface $object
     * 
     * @return True if object already is in the list.
     */
    public function contains(TableElementInterface $object)
    {
        foreach ($this as $element) {
            if ($element->equals($object)) {
                return true;
            }
        }
        return false;
    }
}