<?php

/**
 * Integer column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Class Integer
 * @package Slick\Database\Sql\Ddl\Column
 */
class Integer extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $_nullable = false;

    /**
     * @var string
     */
    protected $_size = Size::NORMAL;

    /**
     * @var int
     */
    protected $_length;

    /**
     * @var int
     */
    protected $_default;

    /**
     * Sets column default value
     *
     * @param int $default
     * @return Integer
     */
    public function setDefault($default)
    {
        $this->_default = $default;
        return $this;
    }

    /**
     * Gets column default value
     *
     * @return int
     */
    public function getDefault()
    {
        return $this->_default;
    }

    /**
     * Sets column length
     *
     * @param int $length
     * @return Integer
     */
    public function setLength($length)
    {
        $this->_length = $length;
        return $this;
    }

    /**
     * Gets column length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->_length;
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
        $this->_nullable = $nullable;
    }

    /**
     * Gets the ISNULL flag for this column
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->_nullable;
    }

    /**
     * Sets column size
     *
     * @param Size $size
     * @returns Integer
     */
    public function setSize(Size $size)
    {
        $this->_size = $size;
    }

    /**
     * Gets column size
     *
     * @return Size
     */
    public function getSize()
    {
        return new Size((string) $this->_size);
    }

} 