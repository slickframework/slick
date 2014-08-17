<?php

/**
 * Blob column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Blob column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Blob extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $_nullable = false;

    /**
     * @var int
     */
    protected $_length;

    /**
     * @var mixed
     */
    protected $_default = null;

    /**
     * Creates a blob field with given name and length
     *
     * @param string $name
     * @param int $length
     * @param array $options
     */
    public function __construct($name, $length, array $options = [])
    {
        $options['length'] = $length;
        parent::__construct($name, $options);
    }

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
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Blob
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
     * Gets column length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->_length;
    }
}
