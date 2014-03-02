<?php

/**
 * WeightList
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Slick\Utility;


use Slick\Common\Base;
use Iterator,
    Countable;

/**
 * WeightList
 *
 * @package    Slick\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class WeightList extends Base implements Iterator , Countable
{

    /**
     * @var array The list of elements
     */
    private $_nodes = [];

    /**
     * @var int Pointer for the current node
     */
    private $_pointer = 0;

    /**
     * Inserts a new object in the list
     *
     * @param $data
     * @param int $weight
     *
     * @return WeightList
     */
    public function insert($data, $weight = 0)
    {
        if ($this->isEmpty()) {
            $this->_nodes[] = ['data' => $data, 'weight' => $weight];
            return $this;
        }

        $inserted = false;
        $new = [];
        while(count($this->_nodes) > 0) {
            $element = array_shift($this->_nodes);
            if (
                !$inserted &&
                $this->compare((int) $weight, $element['weight']) <= 0
            ) {
                $new[] = ['data' => $data, 'weight' => (int) $weight];
                $new[] = ['data' => $element['data'], 'weight' => $element['weight']];
                $inserted = true;
            } else {
                $new[] = ['data' => $element['data'], 'weight' => $element['weight']];
            }
        }
        if (!$inserted) {
            $new[] = ['data' => $data, 'weight' => $weight];
        }
        $this->_nodes = $new;
        return $this;
    }

    /**
     * Removes current object from the list
     *
     * @return WeightList
     */
    public function remove()
    {
        unset($this->_nodes[$this->_pointer]);
        $this->_nodes = array_values($this->_nodes);
        return $this;
    }

    /**
     * Compare elements in order to place them correctly while sifting up.
     *
     * @param $value1
     * @param $value2
     *
     * @return int 0 = Equals, 1 = greater then, -1 less then
     */
    public function compare($value1, $value2)
    {
        if ($value1 === $value2) return 0;
        return ($value1 > $value2) ? 1 : -1;
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     *
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->_nodes[$this->_pointer]['data'];
    }

    /**
     * Returns the current object weight
     *
     * @return int
     */
    public function weight()
    {
        return $this->_nodes[$this->_pointer]['weight'];
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     *
     * @return WeightList
     */
    public function next()
    {
        $this->_pointer++;
        return $this;
    }

    /**
     * Set pointer to the last element of the list
     *
     * @return WeightList
     */
    public function last()
    {
        $this->_pointer = max(($this->count() -1), 0);
        return $this;
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->_pointer;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return boolean The return value will be casted to boolean and
     *  then evaluated. Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->_nodes[$this->_pointer]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     *
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_pointer = 0;
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_nodes);
    }

    /**
     * Returns current list as an array
     *
     * @return array
     */
    public function asArray()
    {
        $array = array();
        foreach ($this->_nodes as $node) {
           $array[] = $node['data'];
        }

        return $array;
    }

    /**
     * Check if current list is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->_nodes);
    }
}