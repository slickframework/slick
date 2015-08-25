<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

/**
 * Mysql SQL dialect
 *
 * @package Slick\Database\Sql\Dialect
 */
class Mysql extends Standard implements DialectInterface
{

    /**
     * Uses for override
     * @var array A map that ties Sql classes to the correspondent template
     */
    protected $map = [
        'Mysql\CreateTableSqlTemplate'
        => 'Slick\Database\Sql\Ddl\CreateTable',
        'Mysql\AlterTableSqlTemplate'
        => 'Slick\Database\Sql\Ddl\AlterTable',
        'Mysql\UpdateSqlTemplate' => 'Slick\Database\Sql\Update',
        'Mysql\SelectSqlTemplate' => 'Slick\Database\Sql\Select'
    ];
}