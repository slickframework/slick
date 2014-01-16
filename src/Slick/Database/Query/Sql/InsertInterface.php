<?php

/**
 * InsertInterface
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

/**
 * InsertInterface defines a Insert SQL statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface InsertInterface extends SqlInterface
{

    /**
     * Sets the data to insert
     * 
     * @param array $data A list of fieldName/value pairs
     *
     * @return \Slick\Database\Query\Sql\InsertInterface A self instance for
     *  method call chains
     */
    public function set(array $data);

    /**
     * Inserts the data in the tatble
     * 
     * @return boolean True if data was successfully saved
     */
    public function save();

}