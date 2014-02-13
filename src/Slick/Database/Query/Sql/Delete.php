<?php

/**
 * Delete
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
 * Delete
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Delete extends AbstractSql implements DeleteInterface
{
    /**
     * Creates a new SQL statement
     * 
     * @param string                               $tableName The database
     *  table for this statement
     * 
     * @param \Slick\Database\Query\QueryInterface $query     The query object
     *  that gives this statement a context
     */
    public function __construct($tableName, QueryInterface $query)
    {
        parent::__construct($tableName, array('*'), $query);
    }

    /**
     * Delete the data in the table
     * 
     * @return boolean True if data was successfully delete
     */
    public function execute()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute($this->params);
    }

    /**
     * Adds conditions to this statement
     *
     * @param array $conditions
     *
     * @return Delete A self instance for method chain calls.
     */
    public function where($conditions)
    {
        return parent::where($conditions);
    }

}