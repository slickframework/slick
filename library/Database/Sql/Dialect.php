<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql;

/**
 * Sql Dialect factory class
 *
 * @package Slick\Database\Sql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Dialect
{

    /**#@+
     * @var string Available dialects
     */
    const STANDARD = 'standard';
    const MYSQL    = 'mysql';
    const SQLITE   = 'sqlite';
    /**#@-*/

    /**
     * @var array A map for known dialect classes
     */
    private static $_map = [
        self::STANDARD => 'Slick\Database\Sql\Dialect\Standard',
        self::MYSQL => 'Slick\Database\Sql\Dialect\Mysql',
        self::SQLITE => 'Slick\Database\Sql\Dialect\Sqlite',
    ];
}