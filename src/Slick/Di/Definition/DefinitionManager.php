<?php

/**
 * DefinitionManager
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;
use Iterator;

/**
 * DefinitionManager
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionManager implements Iterator
{

    /**
     * List of definitions this manager has
     * @var DefinitionInterface[]
     */
    protected $_definitionSource = [];

    /**
     * Definition name index
     * @var array
     */
    protected $_names = [];

    /**
     * Current iterator position
     * @var int
     */
    protected $_position = 0;

    /**
     * Adds a definition to the list of definitions
     *
     * @param DefinitionInterface $definition
     *
     * @returns DefinitionManager
     */
    public function add(DefinitionInterface $definition)
    {
        $index = count($this->_names);
        $this->_definitionSource[$index] = $definition;
        $this->_names[$definition->getName()] = $index;
        return $this;
    }

    /**
     * Checks if a definition with provided name exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->_names);
    }

    /**
     * Returns the definition stored with the provided name
     *
     * @param string $name
     *
     * @return null|\Slick\Di\DefinitionInterface
     */
    public function get($name)
    {
        $definition = null;
        if ($this->has($name)) {
            $definition = $this->_definitionSource[$this->_names[$name]];
        }
        return $definition;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        $current = null;
        if (
            count($this->_names) > 0 &&
            isset($this->_definitionSource[$this->_position])
        ) {
            $current = $this->_definitionSource[$this->_position];
        }
        return $current;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_position += 1;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        $key = null;
        if (count($this->_names) > 0) {
            $names = array_flip($this->_names);
            $key = $names[$this->_position];
        }
        return $key;

    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->_definitionSource[$this->_position]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_position = 0;
    }
}