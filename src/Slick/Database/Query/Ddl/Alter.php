<?php

/**
 * Alter
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl;

/**
 * Alter
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Alter extends Create
{
    /**
     *
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_dropedColumns = null;

    /**
     * Overrides constructor to set initial values on properties
     * 
     * @param strig $tableName The table name for this query statement
     * @param \Slick\Database\Query\QueryInterface $query
     */
    public function __construct(
        $tableName, \Slick\Database\Query\QueryInterface $query)
    {
        parent::__construct($tableName, $query);
        $this->_dropedColumns = new Utility\ElementList();
    }

    /**
     * Sets a columns to be droped (deleted)
     * 
     * @param type $name The column name to remove
     * 
     * @return \Slick\Database\Query\Ddl\Alter A self instance for method call
     *  chains
     */
    public function dropColumn($name)
    {
        $col = new Utility\Column(array('name' => $name));
        $this->_dropedColumns->append($col);
        return $this;
    }
}