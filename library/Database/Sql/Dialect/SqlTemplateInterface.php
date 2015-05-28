<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\SqlInterface;

/**
 * SQL Template interface
 *
 * @package Slick\Database\Sql\Dialect
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SqlTemplateInterface
{
    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param SqlInterface $sql
     *
     * @return string
     */
    public function processSql(SqlInterface $sql);
}