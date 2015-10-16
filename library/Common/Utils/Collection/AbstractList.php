<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils\Collection;

/**
 * Base implementation of an List interface
 *
 * @package Slick\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractList extends AbstractCollection implements ListInterface
{
    /**
     * @readwrite
     * @var int
     */
    protected $size;

    /**
     * Returns the element at the given index.
     *
     * @param integer $index (0-based)
     *
     * @return mixed
     */
    public function get($index)
    {
        return $this->offsetGet($index);
    }

    /**
     * Adds an element to the list.
     *
     * @param mixed $elem
     *
     * @return $this|self|ListInterface
     */
    public function add($elem)
    {
        $this->size = array_push($this->data, $elem);
        return $this;
    }

    /**
     * Removes the element at the given index, and returns it.
     *
     * @param integer $index
     *
     * @return mixed
     */
    public function remove($index)
    {
        $element = $this->get($index);
        $this->offsetUnset($index);
        return $element;
    }

    /**
     * Updates the value at the given index.
     *
     * @param integer $index
     * @param mixed $value
     *
     * @return $this|self|ListInterface
     */
    public function update($index, $value)
    {
        $this->offsetSet($index, $value);
        return $this;
    }

    /**
     * Get current list size (count of elements)
     *
     * @return int
     */
    public function getSize()
    {
        if (is_null($this->size)) {
            $this->size = $this->count();
        }
        return $this->size;
    }
}
