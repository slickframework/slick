<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils\Collection;

use Slick\Common\Utils\CollectionInterface;

/**
 * List Interface is an ordered collection (also known as a sequence)
 *
 * @package Slick\Common\Utils\Collection
 */
interface ListInterface extends CollectionInterface
{

    /**
     * Returns the element at the given index.
     *
     * @param integer $index (0-based)
     *
     * @return mixed
     */
    public function get($index);

    /**
     * Adds an element to the sequence.
     *
     * @param mixed $elem
     *
     * @return $this|self|ListInterface
     */
    public function add($elem);

    /**
     * Removes the element at the given index, and returns it.
     *
     * @param integer $index
     *
     * @return mixed
     */
    public function remove($index);

    /**
     * Updates the value at the given index.
     *
     * @param integer $index
     * @param mixed $value
     *
     * @return $this|self|ListInterface
     */
    public function update($index, $value);

    /**
     * Get current list size (count of elements)
     *
     * @return int
     */
    public function getSize();
}