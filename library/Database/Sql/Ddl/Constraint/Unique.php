<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Unique constraint
 *
 * @package Slick\Database\Sql\Ddl\Constraint
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Unique extends AbstractConstraint implements ConstraintInterface
{

    /**
     * @var string
     */
    protected $column;

    /**
     * Sets unique column name
     *
     * @param string $column
     * @return Unique
     */
    public function setColumn($column)
    {
        $this->column = $column;
        return $this;
    }

    /**
     * Gets unique column name
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }
}
