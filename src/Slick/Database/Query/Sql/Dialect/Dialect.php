<?php

/**
 * Dialect
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect;

/**
 * Dialect defines a SQL dialect to use with database queries
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface Dialect
{

    /**
     * Retrieves the SQL statement for current dialect
     * 
     * @return string The correct SQL statement
     */
    public function getStatement();
}