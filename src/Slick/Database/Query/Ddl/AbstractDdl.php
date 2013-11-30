<?php

/**
 * AbstractDdl
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl;

use Slick\Common\Base,
    Slick\Database\Query\QueryInterface,
    Slick\Database\Query\Sql\SqlInterface;

/**
 * AbstractDdl
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractDdl extends Base implements SqlInterface
{
    /**
     * @readwrite
     * @var \Slick\Database\Query\Query
     */
    protected $_query = null;

    /**
     * @readwrite
     * @var string The table name that will be used in this query
     */
    protected $_tableName;

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
        $this->_query = $query;
        $this->_tableName = $tableName;
        parent::__construct();
    }

    
}