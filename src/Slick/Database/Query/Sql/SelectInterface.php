<?php

/**
 * SelectInterface
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

/**
 * SelectInterface defines a Select SQL statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface SelectInterface extends SqlInterface
{

    /**
     * Returns a RecordList with all records result for this select.
     * 
     * @return \Slick\DataBase\RecordList
     */
    public function all();

    /**
     * Retrieves the first row of a given select query
     * 
     * @return object The record or null.
     */
    public function first();

    /**
     * Count the rows for current where conditions
     * 
     * @return integer The total rows for current conditions
     */
    public function count();

}