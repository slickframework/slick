<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Schema;

use Slick\Common\Base;
use Slick\Database\Adapter\AdapterAwareInterface;
use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Ddl\Column\ColumnInterface;
use Slick\Database\Sql\Ddl\Constraint\ConstraintInterface;
use Slick\Database\Sql\Ddl\CreateTable;

/**
 * Database schema Table
 *
 * @package Slick\Database\Schema
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property AdapterInterface      $adapter     Database adapter
 * @property SchemaInterface       $schema      Database schema
 * @property string                $name        Table name
 * @property ColumnInterface[]     $columns     Table columns
 * @property ConstraintInterface[] $constraints Table constraints
 *
 * @method $this|Table setName(string $name) Set table name
 */
class Table extends Base implements TableInterface
{

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @readwrite
     * @var SchemaInterface
     */
    protected $schema;

    /**
     * @readwrite
     * @var string;
     */
    protected $name;

    /**
     * @readwrite
     * @var ColumnInterface[]
     */
    protected $columns = [];

    /**
     * @readwrite
     * @var ConstraintInterface[]
     */
    protected $constraints = [];

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return AdapterAwareInterface|$this|self
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        if (is_null($this->adapter)) {
            $this->setAdapter($this->getSchema()->getAdapter());
        }
        return $this->adapter;
    }

    /**
     * Adds a column to the table
     *
     * @param ColumnInterface $column
     *
     * @return $this|self
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * Add a constraint to this table
     * @param ConstraintInterface $constraint
     *
     * @return $this|self
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[$constraint->getName()] = $constraint;
        return $this;
    }

    /**
     * Returns the current list of columns
     *
     * @return ColumnInterface[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Returns the current list of constraints
     *
     * @return ConstraintInterface[]
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Set table schema
     *
     * @return SchemaInterface
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Sets the schema for this table
     *
     * @param SchemaInterface $schema
     * @return $this|self
     */
    public function setSchema(SchemaInterface $schema)
    {
        $this->schema = $schema;
        return $this;
    }

    /**
     * Returns table name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the SQL create statement form this table
     *
     * @return string
     */
    public function getCreateStatement()
    {
        $sql = new CreateTable($this->getName());
        $sql->setAdapter($this->getAdapter())
            ->setColumns($this->getColumns())
            ->setConstraints($this->getConstraints());
        return $sql->getQueryString();
    }
}
