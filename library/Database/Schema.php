<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database;

use Slick\Common\Base;
use Slick\Database\Adapter\AdapterAwareInterface;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Schema\SchemaInterface;
use Slick\Database\Schema\TableInterface;

/**
 * Database Schema
 *
 * @package Slick\Database
 *
 * @property TableInterface[] $tables  Schema tables
 * @property string           $name    Schema name
 * @property AdapterInterface $adapter Database adapter
 *
 * @method SchemaInterface|$this setName(string $name) Sets schema name
 */
class Schema extends Base implements SchemaInterface
{
    /**
     * @readwrite
     * @var TableInterface[]
     */
    protected $tables = [];

    /**
     * @readwrite
     * @var string
     */
    protected $name;

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return AdapterAwareInterface
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface|SchemaInterface|$this|self
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Returns the schema tables
     *
     * @return TableInterface[]
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Sets the tables for this schema
     *
     * @param array $tables
     *
     * @return AdapterInterface|SchemaInterface|$this|self
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
     * @return AdapterInterface|SchemaInterface|$this|self
     */
    public function addTable(TableInterface $table)
    {
        $this->tables[$table->getName()] = $table;
        return $this;
    }

    /**
     * Returns schema name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the SQL create statement form this schema
     *
     * @return string
     */
    public function getCreateStatement()
    {
        $statements = [];
        foreach ($this->tables as $table) {
            $table
                ->setSchema($this)
                ->setAdapter($this->getAdapter());
            $statements[] = $table->getCreateStatement();
        }
        return implode(';', $statements);
    }
}
