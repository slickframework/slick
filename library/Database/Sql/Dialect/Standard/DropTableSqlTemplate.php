<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Dialect\FieldListAwareInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Drop Table SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropTableSqlTemplate extends AbstractSqlTemplate
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|FieldListAwareInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $tableName = $sql->getTable();
        return "DROP TABLE {$tableName}";
    }
}