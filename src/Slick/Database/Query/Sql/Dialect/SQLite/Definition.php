<?php

/**
 * Definition
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\SQLite;

use Slick\Common\Base;

/**
 * Definition
 *
 * @package   Slick\Database\Query\Sql\Dialect\SQLite
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Definition extends Base
{
    
    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sql;
    
    /**
     * Returns the SQL query string for current Select SQL Object
     * 
     * @return String The SQL query string
     */
    public function getStatement()
    {
        $name = $this->_sql->tableName;
        return "SELECT * FROM sqlite_master WHERE tbl_name='{$name}'";
    }
}