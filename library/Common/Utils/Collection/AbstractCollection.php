<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils\Collection;

use Slick\Common\Exception\InvalidArgumentException;
use Slick\Common\Utils\CollectionInterface;
use Traversable;

/**
 * Base implementation of a collection interface
 *
 * @package Slick\Common\Utils\Collection
 */
abstract class AbstractCollection implements CollectionInterface
{

    /**
     * @readwrite
     * @var array
     */
    protected $data = [];

    /**
     * @readwrite
     * @var string
     */
    protected $iteratorCLass = self::ITERATOR_CLASS;

    /**
     * Creates the collection with provided data
     *
     * @param array|\Traversable $data
     */
    public function __construct($data = [])
    {
        if ($data instanceof \Traversable || is_array($data)) {
            foreach ($data as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     *
     * @return Traversable An instance of an object implementing Iterator or
     * Traversable
     */
    public function getIterator()
    {
        return new $this->iteratorCLass($this->asArray());
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        unset ($this->data[$offset]);
    }

    /**
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized The string representation of the object.
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Returns current collection as an array
     *
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * Removes all of the elements from this collection
     *
     * @return self|$this|CollectionInterface
     */
    public function clear()
    {
        $this->data = [];
        return $this;
    }

    /**
     * Returns true if this collection contains no elements
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * Iterates over the items in the collection and passes each item to
     * the provided callback function.
     *
     * Return false from your callback to break out of the loop
     *
     * @param callable $callable
     *
     * @return self|$this|CollectionInterface
     */
    public function each(callable $callable)
    {
        foreach ($this->data as $key => &$value) {
            if ($callable($value, $key) === false) {
                break;
            }
        }
        return $this;
    }

    /**
     * Sets the iterator class to instantiate when calling the
     * AbstractCollection::getIterator() method.
     *
     * @param string $className
     *
     * @return $this|self|AbstractCollection
     *
     * @throws InvalidArgumentException If the class does not exists or it does
     *     not implements the Iterator interface.
     *
     * @see AbstractCollection::getIterator()
     */
    public function setIteratorClass($className)
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(
                "Iterator class '{$className}' does not exists."
            );
        }

        $classReflection = new \ReflectionClass($className);
        if (!$classReflection->implementsInterface('Iterator')) {
            throw new InvalidArgumentException(
                "Iterator class '{$className}' does not implements " .
                "'Iterator' interface."
            );
        }

        $this->iteratorCLass = $className;
        return $this;
    }
}