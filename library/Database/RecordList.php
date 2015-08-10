<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Database\Exception\InvalidArgumentException;
use Traversable;

/**
 * Multiple record
 *
 * @package Slick\Database
 * @author  Filipe Silva <filipe.silva@sata.pt>
 *
 * @property string $iteratorClass Iterator class used by getIterator() method
 *
 * @property array $data Record list data
 *
 * @method string getIteratorClass() Returns the iterator class name used by
 *                                   getIterator() method.
 *
 */
class RecordList extends Base implements Countable, ArrayAccess,
    IteratorAggregate
{
    /**
     * @write
     * @var array
     */
    protected $data = [];

    /**
     * @readwrite
     * @var string The iterator class used in the by getIterator
     */
    protected $iteratorClass = 'ArrayIterator';

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The record count as an integer.
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->data);
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
        return isset($this->data[$offset]);
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
        return $this->data[$offset];
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
        $offset = is_scalar($offset)
            ? $offset
            : count($this);

        $this->data[$offset] = $value;
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
        unset($this->data[$offset]);
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
        $inspector = Inspector::forClass($this->iteratorClass);
        return $inspector->getReflection()
            ->newInstanceArgs([$this->data]);
    }

    /**
     * Sets the iterator to use with getIterator() method
     *
     * @param string $class
     * @return RecordList A self instance for method call chains
     *
     * @throws InvalidArgumentException If the provided class
     * does not implement the Traversable or Iterator interfaces.
     */
    public function setIteratorClass($class)
    {
        $inspector = Inspector::forClass($class);
        $isTraversable = $inspector->getReflection()
            ->implementsInterface('Traversable');
        $isIterator = $inspector->getReflection()
            ->implementsInterface('Iterator');

        if (!$isTraversable && !$isIterator) {
            // The provided class is not an iterator or traversable
            throw new InvalidArgumentException(
                "The class {$class} does not implement Traversable or ".
                "Iterator interfaces"
            );
        }
        $this->iteratorClass = $class;
        return $this;
    }

    /**
     * Returns current record data as an array
     *
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * Returns current stored data as an array
     *
     * @return array
     * @deprecated It will be removed in version
     */
    public function getArrayCopy()
    {
        return $this->asArray();
    }
}