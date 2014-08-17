<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 7/25/14
 * Time: 5:37 PM
 */

namespace Slick\Database\Schema;


use Slick\Database\Schema;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;

/**
 * Interface TableInterface
 * @package Slick\Database\Schema
 */
interface TableInterface
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
     * @param Schema $schema
     * @return TableInterface
     */
    public function setSchema(Schema $schema);

    /**
     * Returns table name
     *
     * @return string
     */
    public function getName();
}
