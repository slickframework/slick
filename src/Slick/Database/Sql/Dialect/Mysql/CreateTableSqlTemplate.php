<?php

/**
 * Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Dialect\Mysql;

use Slick\Database\Sql\Ddl\Column;
use Slick\Database\Sql\Dialect\Standard\CreateTableSqlTemplate as StandardTpl;

/**
 * Create Table SQL template
 *
 * @package   Slick\Database\Sql\Dialect\Mysql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateTableSqlTemplate extends StandardTpl
{

    /**
     * Parses a text column to its SQL representation
     *
     * @param Column\Text $column
     * @return string
     */
    protected function _getTextColumn(Column\Text $column)
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
            $this->_nullableColumn($column)
        );
    }

    /**
     * Parses a DateTime column to its SQL representation
     *
     * @param Column\DateTime $column
     * @return string
     */
    protected function _getDateTimeColumn(Column\DateTime $column)
    {
        return sprintf(
            '%s DATETIME%s',
            $column->getName(),
            $this->_nullableColumn($column)
        );
    }
}
