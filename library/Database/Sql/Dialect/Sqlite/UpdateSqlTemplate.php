<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Sqlite;

use Slick\Database\Sql\Dialect\Standard\UpdateSqlTemplate as StandardTpl;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Sql\Update;

/**
 * Class UpdateSqlTemplate
 * @package Slick\Database\Sql\Dialect\Sqlite
 */
class UpdateSqlTemplate extends StandardTpl
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|Update $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        $template = "UPDATE %s SET %s WHERE %s";
        return sprintf(
            $template,
            $this->sql->getTable(),
            $this->getFieldsAndPlaceholders(),
            $this->sql->getWhereStatement()
        );
    }
}
