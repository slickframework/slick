<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Constraint;

use Slick\Common\Utils\ArrayMethods;

/**
 * Primary key constraint
 *
 * @package Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Primary extends AbstractConstraint implements ConstraintInterface
{
    /**
     * @var string[]
     */
    protected $columnNames = [];

    /**
     * Adds column names for primary key definition
     *
     * You can provide an array of names or a string with column names
     * separated a comma.
     *
     * @param string|string[] $columns
     * @return Primary
     */
    public function setColumns($columns)
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
            $columns = ArrayMethods::trim($columns);
        }
        $this->columnNames = $columns;
        return $this;
    }
    /**
     * Returns primary column names
     *
     * @return string[]
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }
}