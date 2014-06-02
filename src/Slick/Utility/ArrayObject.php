<?php

/**
 * ArrayObject
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Utility;

use ArrayObject as SplArrayObject,
    Serializable;

/**
 * Extends the PHPs ArrayObject to fix the serialization bug
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
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