<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/25/14
 * Time: 5:41 PM
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;

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
}
