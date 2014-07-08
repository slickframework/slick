<?php

/**
 * Primary key constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Constraint;

use Slick\Utility\ArrayMethods;

/**
 * Primary key constraint
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Primary extends AbstractConstraint implements ConstraintInterface
{

    /**
     * @var string[]
     */
    protected $_columnNames = [];

    /**
     * Adds column names for primary key definition
     *
     * You can provide an array of names or a string with column names
     * separated a comma.
     *
     * @param string|string[] $columns
     * @return Primary
     */
    public function setColumns($columns)
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
            $columns = ArrayMethods::trim($columns);
        }
        $this->_columnNames = $columns;
        return $this;
    }

    /**
     * Returns primary column names
     *
     * @return string[]
     */
    public function getColumnNames()
    {
        return $this->_columnNames;
    }
}
