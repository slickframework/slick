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
 * Dialect definition interface
 *
 * @package Slick\Database\Sql\Dialect
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface DialectInterface
{
    /**
     * Sets the SQL object to be processed
     *
     * @param SqlInterface $sql
     * @return DialectInterface
     */
    public function setSql(SqlInterface $sql);

    /**
     * Returns the SQL statement for current SQL object
     *
     * @return string
     */
    public function getSqlStatement();
}