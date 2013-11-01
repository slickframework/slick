<?php

/**
 * Database mysql query 
 * 
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

use Slick\Database;

/**
 * Mysql query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Mysql extends Database\Query
{

    /**
     * Handy method to add a 'LEFT' join to the query
     * 
     * @param string $join     The table to join.
     * @param string $onClause The join condition clause
     * @param array  $fields   The list of fields to add to select.
     * 
     * @return \Slick\Database\Query\Mysql Sefl instance for method
     *   chaining calls.
     */
    public function leftJoin($join, $onClause, $fields = array())
    {
        return $this->join($join, $onClause, $fields, 'LEFT');
    }
}
