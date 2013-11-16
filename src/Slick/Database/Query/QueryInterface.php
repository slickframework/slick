<?php

/**
 * QueryInterface
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

/**
 * QueryInterface define a database query behavior
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface QueryInterface
{

    /**
     * Creates a 'Select' SQL statement
     * 
     * @param string $tableName The table name for select statement
     * @param array  $fields    A list of fields to retreive whit query
     * 
     * @return \Slick\Database\Query\Sql\Select The SQL select object
     */
    public function select($tableName, $fields = array('*'));
}