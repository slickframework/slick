<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Table constraint interface
 *
 * @package Slick\Database\Sql\Ddl\Constraint
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ConstraintInterface
{

    /**
     * Returns constraint name
     *
     * @return string
     */
    public function getName();
}