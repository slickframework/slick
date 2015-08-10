<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Sqlite dialect
 *
 * @package Slick\Database\Sql\Dialect
 */
class Sqlite extends Standard
{

    /**
     * Uses for override
     * @var array A map that ties Sql classes to the correspondent template
     */
    protected $map = [
        'Sqlite\CreateTableSqlTemplate'
            => 'Slick\Database\Sql\Ddl\CreateTable',
        'Sqlite\AlterTableSqlTemplate'
            => 'Slick\Database\Sql\Ddl\AlterTable',
        'Sqlite\UpdateSqlTemplate' => 'Slick\Database\Sql\Update',
        'Sqlite\SelectSqlTemplate' => 'Slick\Database\Sql\Select'
    ];
}
