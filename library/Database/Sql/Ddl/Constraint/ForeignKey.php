<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Foreign key constraint
 *
 * @package Slick\Database\Sql\Ddl\Constraint
 */
class ForeignKey extends AbstractConstraint implements ConstraintInterface
{

    /**#@+
     * @var string OnDelete/OnUpdate actions
     */
    const NO_ACTION   = 'NO ACTION';
    const CASCADE     = 'CASCADE';
    const RESTRICTED  = 'RESTRICTED';
    const SET_DEFAULT = 'SET DEFAULT';
    const SET_NULL    = 'SET NULL';
    /**#@-*/

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $referenceTable;

    /**
     * @var string
     */
    protected $referenceColumn;

    /**
     * @var string
     */
    protected $onDelete = self::NO_ACTION;

    /**
     * @var string
     */
    protected $onUpdate = self::NO_ACTION;

    /**
     * Creates a foreign key constraint
     *
     * @param string $name
     * @param string $column
     * @param string $referenceTable
     * @param string $referenceColumn
     * @param array  $options
     */
    public function __construct(
        $name, $column, $referenceTable, $referenceColumn,
        array $options = [])
    {
        $options = array_merge(
            $options,
            [
                'column' => $column,
                'referenceTable' => $referenceTable,
                'referenceColumn' => $referenceColumn
            ]
        );
        parent::__construct($name, $options);
    }

    /**
     * Gets foreign key column name
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Sets on delete action
     *
     * @param string $onDelete
     * @return ForeignKey
     */
    public function setOnDelete($onDelete)
    {
        $this->onDelete = $onDelete;
        return $this;
    }

    /**
     * Gets on delete action
     *
     * @return string
     */
    public function getOnDelete()
    {
        return $this->onDelete;
    }

    /**
     * Sets on update action
     *
     * @param string $onUpdate
     * @return ForeignKey
     */
    public function setOnUpdate($onUpdate)
    {
        $this->onUpdate = $onUpdate;
        return $this;
    }

    /**
     * Gets on update action
     *
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->onUpdate;
    }

    /**
     * Gets reference column name
     *
     * @return string
     */
    public function getReferenceColumn()
    {
        return $this->referenceColumn;
    }

    /**
     * Gets reference table name
     *
     * @return string
     */
    public function getReferenceTable()
    {
        return $this->referenceTable;
    }
}
