<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema;

use Slick\Database\Adapter\AdapterAwareInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Database table interface
 *
 * @package Slick\Database\Schema
 * @author  Filipe Silva <silva.filipe@gmail.com>
 */
interface TableInterface extends AdapterAwareInterface
{

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return TableInterface
     */
    public function addColumn(ColumnInterface $column);

    /**
     * Add a constraint to this table
     * @param ConstraintInterface $constraint
     *
     * @return TableInterface
     */
    public function addConstraint(ConstraintInterface $constraint);

    /**
     * Returns the current list of columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns();

    /**
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints();

    /**
     * Set table schema
     *
     * @return SchemaInterface
     */
    public function getSchema();

    /**
     * Sets the schema for this table
     *
     * @param SchemaInterface $schema
     * @return TableInterface
     */
    public function setSchema(SchemaInterface $schema);

    /**
     * Returns table name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the SQL create statement form this table
     *
     * @return string
     */
    public function getCreateStatement();
}
