<?php

/**
 * Insert statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

use Slick\Database\Query\QueryInterface;

/**
 * Insert statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Insert extends AbstractSql implements InsertInterface
{

    /**
     * @read
     * @var array The fieldNames to update
     */
    protected $_fieldNames = array();

    /**
     * Creates a new SQL statement
     * 
     * @param string                               $tableName The database
     *  table for this statment
     * 
     * @param \Slick\Database\Query\QueryInterface $query     The query object
     *  that gives this statement a context
     */
    public function __construct($tableName, QueryInterface $query)
    {
        parent::__construct($tableName, array('*'), $query);
    }

    /**
     * Sets the data to insert
     * 
     * @param array $data A list of fieldName/value pairs
     *
     * @return \Slick\Database\Query\Sql\InsertInterface A self instance for
     *  method call chains
     */
    public function set(array $data)
    {
        $fieldNames = array();
        $params = $this->getParams();
        foreach ($data as $column => $value) {
            $fieldNames["`{$column}`"] = ":{$column}";
            $params[":{$column}"] = $value;
        }
        $this->_fieldNames = $fieldNames;
        $this->_params = $params;
        return $this;
    }

    /**
     * Inserts the data in the tatble
     * 
     * @return boolean True if data was successfully saved
     */
    public function save()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute($this->params);
    }
}