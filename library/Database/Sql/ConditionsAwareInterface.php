<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * SQL queries with conditions
 *
 * @package Slick\Database\Sql
 */
interface ConditionsAwareInterface extends SqlInterface
{

    /**
     * Returns the where statement for sql
     *
     * @return null|string
     */
    public function getWhereStatement();
}