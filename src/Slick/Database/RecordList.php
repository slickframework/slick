<?php

/**
 * Record list
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database;

use Countable,
    ArrayAccess,
    Traversable,
    Serializable,
    ReflectionClass,
    IteratorAggregate;

use Slick\Common\Base,
    Slick\Database\Exception\InvalidArgumentException;

/**
 * Record list
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RecordList extends Base implements Countable, ArrayAccess,
    IteratorAggregate, Serializable
{

    /**
     * @write
     *
     * @var array Stores the records
     */
    protected $_data = [];

    /**
     * @readwrite
     * @var string
     */
    protected $_iteratorClass = 'ArrayIterator';

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->_data[$offset];
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_scalar($offset)) {
            $this->_data[$offset] = $value;
        } else {
            $this->_data[] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[0]);
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing Iterator or
     *  Traversable interfaces
     */
    public function getIterator()
    {
        $reflection = new ReflectionClass($this->_iteratorClass);
        return $reflection->newInstanceArgs([$this->_data]);
    }

    /**
     * Sets the iterator to use with getIterator() method
     *
     * @param string $class
     * @return RecordList A self instance for method call chains
     *
     * @throws Exception\InvalidArgumentException If the provided class
     * does not implement the Traversable or Iterator interfaces.
     */
    public function setIteratorClass($class)
    {
        $reflection = new ReflectionClass($class);
        if (
            !$reflection->implementsInterface('Traversable') &&
            !$reflection->implementsInterface('Iterator')
        ) {
            // The provided class is not an iterator or traversable
            throw new InvalidArgumentException(
                "The class {$class} does not implement Traversable or " .
                "Iterator interfaces"
            );
        }

        $this->_iteratorClass = $class;
        return $this;
    }

    /**
     * Returns current stored data as an array
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->_data;
    }

    /**
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        $data = [
            'data' => $this->_data,
            'iteratorClass' => $this->_iteratorClass
        ];
        return serialize($data);
    }

    /**
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->_data = $data['data'];
        $this->_iteratorClass = $data['iteratorClass'];
        parent::__construct();
    }
}