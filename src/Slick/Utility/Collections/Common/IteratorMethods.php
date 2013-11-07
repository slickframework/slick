<?php

/**
 * Iterator method 
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Collections\Common;

/**
 * Interator methods for classes implementing SPL Iterator interface.
 *
 * The class that uses this trait must extend Slick\Common\Base and need to
 * define a protected property $_elements as an array and a protected proerty
 * $_position as integer.
 *
 * @package   Slick\Utility\Collections\Common
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait IteratorMethods
{

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * Return the current element
     * 
     * @return mixed|object The element at current position
     */
    public function current()
    {
        if ($this->valid())
            return $this->_elements[$this->_position];
        return null;
    }

    /**
     * Return the key of the current element
     * 
     * @return integer The current element position
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        ++$this->_position;
    }

    /**
     * Checks if current position is valid
     * 
     * @return boolean True if current position is set and has an element
     *   or false if current position isn't set.
     */
    public function valid()
    {
        return isset($this->_elements[$this->_position]);
    }
}