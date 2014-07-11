<?php

/**
 * Foreign key constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Foreign key constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ForeignKey extends AbstractConstraint implements ConstraintInterface
{

    /**+@#
     * OnDelete/OnUpdate actions
     * @var string
     */
    const NO_ACTION   = 'NO ACTION';
    const CASCADE     = 'CASCADE';
    const RESTRICTED  = 'RESTRICTED';
    const SET_DEFAULT = 'SET DEFAULT';
    const SET_NULL    = 'SET NULL';
    /**-@#*/

    /**
     * @var string
     */
    protected $_column;

    /**
     * @var string
     */
    protected $_referenceTable;

    /**
     * @var string
     */
    protected $_referenceColumn;

    /**
     * @var string
     */
    protected $_onDelete = self::NO_ACTION;

    /**
     * @var string
     */
    protected $_onUpdate = self::NO_ACTION;

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
        $options = array_merge($options, [
            'column' => $column,
            'referenceTable' => $referenceTable,
            'referenceColumn' => $referenceColumn
        ]);
        parent::__construct($name, $options);
    }


    /**
     * Gets foreign key column name
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->_column;
    }

    /**
     * Sets on delete action
     *
     * @param string $onDelete
     * @return ForeignKey
     */
    public function setOnDelete($onDelete)
    {
        $this->_onDelete = $onDelete;
        return $this;
    }

    /**
     * Gets on delete action
     *
     * @return string
     */
    public function getOnDelete()
    {
        return $this->_onDelete;
    }

    /**
     * Sets on update action
     *
     * @param string $onUpdate
     * @return ForeignKey
     */
    public function setOnUpdate($onUpdate)
    {
        $this->_onUpdate = $onUpdate;
        return $this;
    }

    /**
     * Gets on update action
     *
     * @return string
     */
    public function getOnUpdate()
    {
        return $this->_onUpdate;
    }

    /**
     * Gets reference column name
     *
     * @return string
     */
    public function getReferenceColumn()
    {
        return $this->_referenceColumn;
    }

    /**
     * Gets reference table name
     *
     * @return string
     */
    public function getReferenceTable()
    {
        return $this->_referenceTable;
    }
}
