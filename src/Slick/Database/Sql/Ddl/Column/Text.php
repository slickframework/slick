<?php

/**
 * Text column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Text column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Text extends AbstractColumn
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
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Text
     */
    public function setNullable($nullable)
    {
        $this->_nullable = $nullable;
        return $this;
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
     * @returns Text
     */
    public function setSize(Size $size)
    {
        $this->_size = $size;
        return $this;
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
