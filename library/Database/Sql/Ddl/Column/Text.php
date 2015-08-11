<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Text column
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Text extends AbstractColumn
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
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Text
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
     * @return Text
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
}