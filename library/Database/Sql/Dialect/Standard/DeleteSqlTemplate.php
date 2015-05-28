<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Delete;
use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Delete SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DeleteSqlTemplate extends AbstractSqlTemplate implements
    SqlTemplateInterface
{
    /**
     * @var Delete
     */
    protected $sql;

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        $this->statement = "DELETE FROM {$this->sql->getTable()}";
        $this->getWhereConditions();
        return $this->statement;
    }
}