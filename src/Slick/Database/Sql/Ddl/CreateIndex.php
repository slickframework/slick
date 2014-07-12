<?php

/**
 * Create table index SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl;

use Slick\Database\Sql\Dialect;
use Slick\Utility\ArrayMethods;
use Slick\Database\Sql\AbstractSql;
use Slick\Database\Sql\SqlInterface;

/**
 * Create table index SQL statement
 *
 * @package   Slick\Database\Sql\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CreateIndex extends AbstractSql implements SqlInterface
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string[]
     */
    protected $_columnNames = [];

    /**
     * Creates the sql with the table name
     *
     * @param $name
     * @param string $tableName
     */
    public function __construct($name, $tableName)
    {
        $this->_name = $name;
        $this->_table = $tableName;
    }

    /**
     * Returns index name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
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
     * @return $this
     */
    public function setColumnNames(array $columns)
    {
        $this->_columnNames = $columns;
        return $this;
    }

    /**
     * Returns the list of index column names
     *
     * @return string[]
     */
    public function getColumnNames()
    {
        return $this->_columnNames;
    }

    /**
     * Returns the string version of this query
     *
     * @return string
     */
    public function getQueryString()
    {
        $dialect = Dialect::create($this->_adapter->getDialect(), $this);
        return $dialect->getSqlStatement();
    }
}
