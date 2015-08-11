<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Integer type column
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class Integer extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @var Size|string
     */
    protected $size = Size::NORMAL;

    /**
     * @var bool
     */
    protected $autoIncrement = false;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $default = 0;

    /**
     * Sets column default value
     *
     * @param int $default
     * @return Integer
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Gets column default value
     *
     * @return int
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets column length
     *
     * @param int $length
     * @return Integer
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Gets column length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Integer
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * Gets the ISNULL flag for this column
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->nullable;
    }

    /**
     * Sets column size
     *
     * @param Size $size
     * @return Integer
     */
    public function setSize(Size $size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Gets column size
     *
     * @return Size
     */
    public function getSize()
    {
        return new Size((string) $this->size);
    }

    /**
     * Sets auto increment flag state
     *
     * @param boolean $autoIncrement
     * @return Integer
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    /**
     * Gets auto increment flag state
     * @return boolean
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }
}