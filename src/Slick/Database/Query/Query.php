<?php

/**
 * Query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

use Slick\Database\Query\Sql;

/**
 * Query represents a database query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Query extends AbstractQuery implements QueryInterface
{
    /**
     * Creates a 'Select' SQL statement
     * 
     * @param string $tableName The table name for select statement
     * @param array  $fields    A list of fields to retrieve whit query
     * 
     * @return \Slick\Database\Query\Sql\Select The SQL select object
     */
    public function select($tableName, $fields = array('*'))
    {
        $this->_sqlStatement = new Sql\Select($tableName, $fields, $this);
        return $this->_sqlStatement;
    }

    /**
     * Creates a 'Insert' SQL statement
     * 
     * @param string $tableName The table name for insert statement
     * @return \Slick\Database\Query\Sql\Insert The SQL insert object
     */
    public function insert($tableName)
    {
        $this->_sqlStatement = new Sql\Insert($tableName, $this);
        return $this->_sqlStatement;
    }

    /**
     * Creates a 'Update' SQL statement
     * 
     * @param string $tableName The table name for update statement
     * @return \Slick\Database\Query\Sql\Update The SQL update object
     */
    public function update($tableName)
    {
        $this->_sqlStatement = new Sql\Update($tableName, $this);
        return $this->_sqlStatement;
    }

    /**
     * Creates a 'Delete' SQL statement
     * 
     * @param string $tableName The table name for delete statement
     * @return \Slick\Database\Query\Sql\Delete The SQL delete object
     */
    public function delete($tableName)
    {
        $this->_sqlStatement = new Sql\Delete($tableName, $this);
        return $this->_sqlStatement;
    }

    
}