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

use Slick\Database\Exception,
    Slick\Utility\ArrayObject;

/**
 * ElementList - List of table elements (columns, indexes, foreignKeys)
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ElementList extends ArrayObject
{
    
    /**
     * Appends a table element to the element list
     * 
     * @param \Slick\Database\Query\Ddl\Utility\TableElementInterface $value
     */
    public function append($value)
    {
        if ($value instanceof TableElementInterface) {
            $this[] = $value;
        }

    }

    /**
     * Updates or inserts the element at a given offset
     *
     * Overrides the ArrayObject::offsetSet() to force the insertion of
     * TableElementsInterface only.
     *
     * @param mixed $offset The index being set.
     * @param mixed $value The new value for the index.
     * @throws \Slick\Database\Exception\InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (
            !is_a(
                $value,
                'Slick\Database\Query\Ddl\Utility\TableElementInterface'
            )
        ) {
            throw new Exception\InvalidArgumentException(
                "Table element list only accepts TableElementInterface objects."
            );
            
        }
        parent::offsetSet($offset, $value);
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

    /**
     * Traverses the element list to find an element with the provided name
     * 
     * @param string $name The element name to find
     * 
     * @return TableElementInterface|false The matched table element or
     *  boolean false if not found. 
     */
    public function findByName($name)
    {
        $match = false;
        foreach ($this as $element) {
            if ($element->name == $name) {
                $match = $element;
                break;
            }
        }
        return $match;
    }
}