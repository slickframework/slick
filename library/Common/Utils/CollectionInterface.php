<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils;

/**
 * Interface Collection is a container type object that groups multiple
 * elements into a single object.
 *
 * @package Slick\Common\Utils
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface CollectionInterface extends
    \Countable, \IteratorAggregate, \ArrayAccess, \Serializable
{

    /**
     * The default iterator class name
     */
    const ITERATOR_CLASS = 'ArrayIterator';

    /**
     * Returns current collection as an array
     *
     * @return array
     */
    public function asArray();

    /**
     * Removes all of the elements from this collection
     *
     * @return self|$this|CollectionInterface
     */
    public function clear();

    /**
     * Returns true if this collection contains no elements
     *
     * @return boolean
     */
    public function isEmpty();

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
    public function each(callable $callable);

}