<?php

/**
 * Schema
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database;

use Slick\Common\BaseMethods;
use Slick\Database\Schema\TableInterface;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Adapter\AdapterInterface;

/**
 * Encapsulation of a database schema
 *
 * @package   Slick\Database
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property TableInterface[] $tables
 * @property AdapterInterface $adapter
 */
class Schema implements SchemaInterface
{
    /**
     * Factory behavior methods from Slick\Common\Base class
     */
    use BaseMethods;

    /**
     * @readwrite
     * @var TableInterface[]
     */
    protected $_tables = [];

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $_adapter = null;

    /**
     * Factory method for schema creation
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->_createObject($options);
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     *
     * @return Schema
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Returns the schema tables
     *
     * @return TableInterface[]
     */
    public function getTables()
    {
        return $this->_tables;
    }

    /**
     * Sets the tables for this schema
     *
     * @param array $tables
     *
     * @return SchemaInterface
     */
    public function setTables(array $tables)
    {
        foreach ($tables as $table) {
            $this->addTable($table);
        }
        return $this;
    }

    /**
     * Adds a table to this schema
     *
     * @param TableInterface $table
     *
     * @return SchemaInterface
     */
    public function addTable(TableInterface $table)
    {
        $this->_tables[$table->getName()] = $table;
        return $this;
    }
}
