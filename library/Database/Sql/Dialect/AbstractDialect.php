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
 * Abstract SQL Dialect
 *
 * @package Slick\Database\Sql\Dialect
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDialect implements DialectInterface
{

    /**
     * @var SqlInterface
     */
    protected $sql;

    /**
     * Sets the SQL object to be processed
     *
     * @param SqlInterface $sql
     *
     * @return AbstractDialect
     */
    public function setSql(SqlInterface $sql)
    {
        $this->sql = $sql;
        return $this;
    }
}