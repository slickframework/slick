<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Ddl\CreateIndex;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Create Index SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class CreateIndexSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{

    /**
     * @var SqlInterface|CreateIndex
     */
    protected $sql;

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface|CreateIndex $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        /** @var CreateIndex $sql */
        $this->sql = $sql;
        $template = "CREATE INDEX %s ON %s (%s)";
        return sprintf(
            $template,
            $this->sql->getName(),
            $this->sql->getTable(),
            implode(', ', $this->sql->getColumnNames())
        );
    }
}