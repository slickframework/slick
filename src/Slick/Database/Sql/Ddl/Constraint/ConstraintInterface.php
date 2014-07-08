<?php

/**
 * Table constraint interface
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Table constraint interface
 *
 * @package   Slick\Database\Sql\Ddl\Constraint
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface ConstraintInterface
{

    /**
     * Returns constraint name
     *
     * @return string
     */
    public function getName();
}
