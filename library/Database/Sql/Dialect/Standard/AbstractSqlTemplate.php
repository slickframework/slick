<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Dialect\Standard;

use Slick\Database\Sql\Dialect\SqlTemplateInterface;
use Slick\Database\Sql\SqlInterface;

/**
 * Abstract SQL template
 *
 * @package Slick\Database\Sql\Dialect\Standard
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractSqlTemplate implements SqlTemplateInterface
{

    /**
     * @var SqlInterface
     */
    protected $sql;

    /**
     * @var string
     */
    protected $statement = '';

    /**
     * Sets the where clause for this select statement
     *
     * @return SqlTemplateInterface|SelectSqlTemplate
     */
    protected function getWhereConditions()
    {
        if (method_exists($this->sql, 'getWhereStatement')) {
            $where = $this->sql->getWhereStatement();
            if (!is_null($where)) {
                $this->statement .= " WHERE {$where}";
            }
        }
        return $this;
    }
}