<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Common\Utils\ArrayMethods;
use Slick\Database\Sql\AbstractExecutionOnlySql;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;

/**
 * Create index SQL statement
 *
 * @package Slick\Database\Sql\Ddl
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateIndex extends AbstractExecutionOnlySql implements SqlInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $columnNames = [];

    /**
     * Creates the sql with the table name
     *
     * @param string $name
     * @param string $tableName
     */
    public function __construct($name, $tableName)
    {
        $this->name = $name;
        parent::__construct($tableName);
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
     * Adds column names for primary key definition
     *
     * You can provide an array of names or a string with column names
     * separated a comma.
     *
     * @param string|string[] $columns
     * @return CreateIndex
     */
    public function setColumns($columns)
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
            $columns = ArrayMethods::trim($columns);
        }
        return $this->setColumnNames($columns);
    }

    /**
     * Sets the internal array of column names
     *
     * @param string[] $columns
     * @return self
     */
    public function setColumnNames(array $columns)
    {
        $this->columnNames = $columns;
        return $this;
    }

    /**
     * Returns the list of index column names
     *
     * @return string[]
     */
    public function getColumnNames()
    {
        return $this->columnNames;
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
