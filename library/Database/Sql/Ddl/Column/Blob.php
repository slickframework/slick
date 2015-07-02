<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Blob column
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Blob extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $nullable = false;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var mixed
     */
    protected $default = null;

    /**
     * Creates a blob field with given name and length
     *
     * @param string $name    Column name
     * @param int    $length  Column length
     * @param array  $options Other optional column attributes
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
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Blob
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
     * Gets column length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }
}