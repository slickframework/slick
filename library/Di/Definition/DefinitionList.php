<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Definition;

use Slick\Common\Utils\ArrayObject;
use Slick\Di\DefinitionInterface;
use Slick\Di\Exception\InvalidArgumentException;

/**
 * A list of definitions
 *
 * @package Slick\Di\Definition
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionList extends ArrayObject
{

    /**
     * Appends a definition to the list
     *
     * @param DefinitionInterface $value
     */
    public function append($value)
    {
        $index = $this->count();
        if ($value instanceof DefinitionInterface) {
            $index = $value->getName();
        }
        $this->offsetSet($index, $value);
    }

    /**
     * Sets the value on a specific offset index
     *
     * @param mixed $offset The index being set.
     * @param mixed $value  The new value for the index.
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof DefinitionInterface)) {
            throw new InvalidArgumentException(
                "Trying to add an object to definition list that does ".
                "not implement 'DefinitionInterface'."
            );
        }

        parent::offsetSet($offset, $value);
    }
}