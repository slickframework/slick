<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Varchar type column
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Varchar extends AbstractColumn
{

    /**
     * @var int
     */
    protected $length;

    /**
     * Sets name and length
     *
     * @param string  $name
     * @param integer $length
     */
    public function __construct($name, $length)
    {
        parent::__construct($name, ['length' => $length]);
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