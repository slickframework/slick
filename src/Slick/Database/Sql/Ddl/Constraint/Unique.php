<?php

/**
 * Unique constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Unique constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Unique extends AbstractConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected $_column;

    /**
     * Sets unique column name
     *
     * @param string $column
     * @return Unique
     */
    public function setColumn($column)
    {
        $this->_column = $column;
        return $this;
    }

    /**
     * Gets unique column name
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->_column;
    }
}
