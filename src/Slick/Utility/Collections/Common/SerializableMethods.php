<?php

/**
 * SerializableMethods
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections\Common;

/**
 * Serializable methods for classes implementing SPL Serializable interface.
 *
 * The class that uses this trait must extend Slick\Common\Base and need to
 * define a protected property $_elements as an array.
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait SerializableMethods
{
    /**
     * Serializes the collection object
     * 
     * @return string String representation of this collection
     */
    public function serialize()
    {
        return serialize($this->_elements);
    }

    /**
     * Unserializes the provided string to a collection
     * 
     * @param string $serialized String representation of a collection
     * 
     * @return \Slick\Utility\Collections\Collection A collection object
     */
    public function unserialize($serialized)
    {
        parent::__construct(array());
        $this->_elements = unserialize($serialized);
    }
}