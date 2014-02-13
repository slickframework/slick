<?php

/**
 * TagValues
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Inspector;

use ArrayIterator;

/**
 *
 * TagValues
 *
 * @package   Slick\Common\Inspector
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TagValues extends ArrayIterator
{
    /**
     * Check is a value exists
     *
     * @param string $tagValue The tag value to check
     *
     * @return bool True if value contains the provided tag
     */
    public function check($tagValue)
    {
        foreach ($this as $key => $value) {
            if ($value == $tagValue || $key == $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the given index exists to prevent invalid index error
     *
     * @param string $index
     * @return mixed|null
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            return null;
        }
        return parent::offsetGet($index);
    }
} 