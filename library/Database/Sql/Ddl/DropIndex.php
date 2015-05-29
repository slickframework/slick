<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\AbstractExecutionOnlySql;
use Slick\Database\Sql\Dialect;

/**
 * Drop table index SQL statement
 *
 * @package Slick\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DropIndex extends AbstractExecutionOnlySql
{

    /**
     * @var string
     */
    protected $name;

    /**
     * Creates the sql with the table name
     *
     * @param string $name
     * @param string $tableName
     */
    public function __construct($name, $tableName)
    {
        $this->name = $name;
        $this->table = $tableName;
    }

    /**
     * Returns index name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }

}