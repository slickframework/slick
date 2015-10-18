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
 * Map Interface
 *
 * @package Slick\Common\Utils\Collection
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MapInterface extends CollectionInterface
{

    /**
     * Puts a new element in the map.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return $this|self|MapInterface
     */
    public function set($key, $value);

    /**
     * Returns the value associated with the given key.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Removes an element from the map and returns it.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key);

    /**
     * Returns whether this map contains a given key.
     *
     * @param mixed $key
     *
     * @return boolean
     */
    public function containsKey($key);

    /**
     * Returns an array with the keys.
     *
     * @return array
     */
    public function keys();

    /**
     * Returns an array with the values.
     *
     * @return array
     */
    public function values();
}
