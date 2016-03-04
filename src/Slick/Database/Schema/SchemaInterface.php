<?php

/**
 * Schema interface
 *
 * @package   Slick\Database\Schema
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;

/**
 * Interface SchemaInterface
 * @package Slick\Database\Schema
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
