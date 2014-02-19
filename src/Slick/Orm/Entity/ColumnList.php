<?php

/**
 * ColumnList
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;

use ArrayObject;
use Slick\Orm\Exception;

/**
 * ColumnList
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ColumnList extends ArrayObject
{

    /**
     * Appends a column to the list
     *
     * @param Column $value The column object to append
     *
     * @return ColumnList
     */
    public function append($value)
    {
        $this[$value->name] = $value;
        return $this;
    }

    /**
     * Adds a column to te list
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws \Slick\Orm\Exception\InvalidArgumentException If the value
     *  given isn't an Column object
     */
    public function offsetSet($offset, $value)
    {
        if (!is_a($value, 'Slick\Orm\Entity\Column')) {
            throw new Exception\InvalidArgumentException(
                "Column list accepts only Column objects."
            );
        }
        $offset = $value->name;
        parent::offsetSet($offset, $value);
    }

    /**
     * Check if this list has one primary key column defined
     *
     * @return boolean True if the list as one primary key column,
     * false otherwise
     */
    public function hasPrimaryKey()
    {
        /** @var $column Column */
        foreach ($this as $column) {
            if ($column->primaryKey) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if a column with provided name exists in the list
     *
     * @param string $name Column name to search
     *
     * @return bool True if there is a column with given name, false otherwise
     */
    public function hasColumn($name)
    {
        /** @var $column Column */
        foreach ($this as $column) {
            if ($column->name == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the column with the given name from the list
     *
     * If column does not exists null will be returned
     *
     * @param string $name Name of the column to retrieve
     *
     * @return Column|null The column object or null in not found
     */
    public function get($name)
    {
        if ($this->hasColumn($name)) {
            return $this[$name];
        }
        return null;
    }
} 