<?php

/**
 * DDLQuery
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

/**
 * DDLQuery
 *
 * @package   Slick\Database\DDLQuery
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DDLQuery extends AbstractQuery implements QueryInterface
{
    
    /**
     * Creates a 'CREATE TABLE' SQL statement
     * 
     * @param string $tableName The table name for CREATE TABLE statement
     * @return \Slick\Database\Query\Ddl\Create The SQL CREATE TABLE object
     */
    public function create($tableName)
    {
        $this->_sqlStatement = new Ddl\Create($tableName, $this);
        return $this->_sqlStatement;
    }


}