<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\DropIndex;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Drop Index SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropIndexSqlTemplate extends AbstractSqlTemplate
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        /** @var DropIndex $sql */
        $this->sql = $sql;
        $template = "DROP INDEX %s ON %s";
        return sprintf($template, $sql->getName(), $sql->getTable());
    }
}