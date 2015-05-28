<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * SQL Query interface to use with adapter execute() and query() methods
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SqlInterface
{

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString();

    /**
     * Returns the parameters to be bound to query string by adapter
     *
     * @return array
     */
    public function getParameters();
}