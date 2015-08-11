<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Insert;
use Slick\Database\Sql\SqlInterface;

/**
 * Standard Insert SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class InsertSqlTemplate extends AbstractSqlTemplate
{

    /**
     * @var SqlInterface|Insert
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
        $template = "INSERT INTO %s (%s) VALUES (%s)";
        return sprintf(
            $template,
            $this->sql->getTable(),
            $this->sql->getFieldList(),
            $this->sql->getPlaceholderList()
        );
    }
}