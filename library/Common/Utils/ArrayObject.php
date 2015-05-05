<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Utils;

use Serializable;
use ArrayObject as SplArrayObject;

/**
 * Extends the PHPs ArrayObject to fix the serialization bug
 *
 * @see https://bugs.php.net/bug.php?id=62672
 *
 * @package Slick\Common\Utils
 */
class ArrayObject extends SplArrayObject implements Serializable
{

    /**
     * Serialize an ArrayObject
     *
     * @return string|void
     */
    public function serialize()
    {
        $data = $this->getArrayCopy();
        return serialize($data);
    }
    /**
     * Unserialize an ArrayObject
     *
     * @param string $serialized
     * return ArrayObject
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        foreach ($data as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }
}
