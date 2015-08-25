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
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function getTextColumn(Column\Text $column)
    {
        $size = (string) $column->getSize();
        switch ($size) {
            case Column\Size::LONG:
            case Column\Size::BIG:
                $type = 'LONGTEXT';
                break;

            case Column\Size::SMALL:
            case Column\Size::TINY:
                $type = 'TINYTEXT';
                break;

            case Column\Size::NORMAL:
            case Column\Size::MEDIUM:
            default:
                $type = 'TEXT';
        }

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
