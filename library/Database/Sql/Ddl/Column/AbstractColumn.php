<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Abstract column - Basic column implementation
 *
 * @package Slick\Database\Sql\Ddl\Column
 * @author  Filipe Silva <silvam.filipe@gmai.com>
 */
abstract class AbstractColumn implements ColumnInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * Creates a DDL column
     *
     * @param string $name Column name
     *
     * @param array $options
     * Options can be (depending on the column type):
     *  - length
     *  - size
     *  - nullable
     *  - default
     *  - autoIncrement
     *  - precision
     *  - digits
     *  - decimal
     *  - autoIncrement
     */
    public function __construct($name, $options = [])
    {
        $this->name = $name;
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Returns column name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}