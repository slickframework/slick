<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;

/**
 * Database Schema interface
 *
 * @package Slick\Database\Schema
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SchemaInterface extends AdapterAwareInterface
{
    /**
     * Returns the schema tables
     *
     * @return TableInterface[]
     */
    public function getTables();

    /**
     * Sets the tables for this schema
     *
     * @param array $tables
     *
     * @return SchemaInterface
     */
    public function setTables(array $tables);

    /**
     * Adds a table to this schema
     *
     * @param TableInterface $table
     *
     * @return SchemaInterface
     */
    public function addTable(TableInterface $table);

    /**
     * Returns schema name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the SQL create statement form this schema
     *
     * @return string
     */
    public function getCreateStatement();
}
