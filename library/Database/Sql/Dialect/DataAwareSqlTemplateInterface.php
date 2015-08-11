<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect;

use Slick\Database\Sql\DataSetInterface;

/**
 * Data aware Sql Template Interface
 *
 * @package Slick\Database\Sql\Dialect
 */
interface DataAwareSqlTemplateInterface
{

    /**
     * Processes the SQL object and returns the SQL statement
     *
     * @param DataSetInterface $sql
     *
     * @return string
     */
    public function processSql(DataSetInterface $sql);
}