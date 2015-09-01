<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Dialect\Standard\CreateTableSqlTemplate as StandardTpl;

/**
 * Create Table SQL template
 *
 * @package Slick\Database\Sql\Dialect\Mysql
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends StandardTpl
{
    /**
     * @var array column sizes map
     */
    private static $textColumnSizes = [
        Column\Size::SMALL  => 'TINYTEXT',
        Column\Size::TINY   => 'TINYTEXT',
        Column\Size::NORMAL => 'TEXT',
        Column\Size::MEDIUM => 'TEXT',
        Column\Size::LONG   => 'LONGTEXT',
        Column\Size::BIG    => 'LONGTEXT',
    ];

    /**
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function getTextColumn(Column\Text $column)
    {
        $size = (string) $column->getSize();
        $type = self::$textColumnSizes[$size];

        return sprintf(
            '%s %s%s',
            $column->getName(),
            $type,
            $this->nullableColumn($column)
        );
    }

    /**
     * Parses a DateTime column to its SQL representation
     *
     * @param Column\DateTime $column
     * @return string
     */
    protected function getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s DATETIME%s',
            $column->getName(),
            $this->nullableColumn($column)
        );
    }
}
