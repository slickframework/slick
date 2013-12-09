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

use Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Database\Query\Ddl\Utility\Column,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
    Slick\Database\Query\Ddl\Utility\Index;
use Symfony\Component\EventDispatcher\Tests\CallableClass;

/**
 * Alter
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Alter extends Create
{
    /**
     * @readwrite
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_droppedColumns = null;
    
    /**
     * @readwrite
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_changedColumns = null;
    
    /**
     * @readwrite
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_droppedForeignKeys = null; 
    
    /**
     * @readwrite
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_droppedIndexes = null;

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
        $this->_droppedColumns = new ElementList();
        $this->_changedColumns = new ElementList();
        $this->_droppedForeignKeys = new ElementList();
        $this->_droppedIndexes = new ElementList();
    }

    /**
     * Sets a columns to be dropped (deleted)
     * 
     * @param type $name The columns name to remove
     * 
     * @return \Slick\Database\Query\Ddl\Alter A self instance for method call
     *  chains
     */
    public function dropColumn($name)
    {
        $col = new Utility\Column(array('name' => $name));
        $this->_droppedColumns->append($col);
        return $this;
    }

    /**
     * Changes an existing column in the table
     *  
     * @param string|Column $name The new column name or a Column object.
     * @param array $options A list of options for the column
     * 
     * @see \Slick\Database\Query\Ddl\Utility\Column
     * 
     * @return \Slick\Database\Query\Ddl\Alter
     */
    public function changeColumn($name, $options = array())
    {
        if (is_a($name, 'Slick\Database\Query\Ddl\Utility\Column')) {
            $this->_changedColumns->append($name);
        } else {
            $options['name'] = $name;
            $this->_changedColumns->append(new Column($options));
        }
        
        return $this;
    }
    
    /**
     * Sets a foreign key to be deleted
     * 
     * @param string $name Foreign key constraint name
     * 
     * @return \Slick\Database\Query\Ddl\Alter A self instance for method
     *  call chains.
     */
    public function dropForeignKey($name)
    {
        $frk = new ForeignKey();
        $frk->setName($name);
        $this->_droppedForeignKeys->append($frk);
        return $this;
    }
    
    /**
     * Sets an index to be deleted
     * 
     * @param string $column The column name
     * 
     * @return \Slick\Database\Query\Ddl\Alter
     */
    public function dropIndex($column)
    {
        $idx = new Index(
            array(
                'name' => "{$column}_idx",
                'indexColumns' => array($column),
            )
        );
        $this->_droppedIndexes->append($idx);
        return $this;
    }

    /**
     * Sets a table option to this alter statement
     * 
     * @param string $name  Option name
     * @param string $value Option value
     *
     * @return \Slick\Database\Query\Ddl\Alter A self instance for method
     *  call chains
     */
    public function setOption($name, $value)
    {
        return $this->addOption($name, $value);
    }
}