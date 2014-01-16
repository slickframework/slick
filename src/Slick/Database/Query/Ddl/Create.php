<?php

/**
 * Create
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl;

use Slick\Database\Query\Ddl\Utility\ElementList,
    Slick\Database\Query\Ddl\Utility\ForeignKey,
    Slick\Database\Query\Ddl\Utility\Index,
    Slick\Database\Query\QueryInterface,
    Slick\Database\Exception;

/**
 * Create
 *
 * @package   Slick\Database\Query\Ddl
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Create extends AbstractDdl
{
    
    /**
     * @read
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_columns;

    /**
     * @readwrite
     * @var Slick\Database\Query\Ddl\Utility
     */
    protected $_indexes;

    /**
     * @read
     * @var Slick\Database\Query\Ddl\Utility
     */
    protected $_foreignKeys;

    /**
     * @readwrite
     * @var array A list of table options
     */
    protected $_options = array();

    /**
     * Overrides default constructor to initilize the table elements lists
     * 
     * @param strig $tableName The table name for this query statement
     * @param \Slick\Database\Query\QueryInterface $query
     */
    public function __construct($tableName, QueryInterface $query)
    {
        $this->_columns = new ElementList();
        $this->_indexes = new ElementList();
        $this->_foreignKeys = new ElementList();

        parent::__construct($tableName, $query);
    }

    /**
     * Adds a column to current create query
     * 
     * @param string $name    The column name
     * @param array  $options Column definitions (size, type, etc..)
     *
     * @return \Slick\Database\Query\Ddl\Create A self instance for method
     *  call cahins
     */
    public function addColumn($name, $options = array())
    {
        $options['name'] = $name;
        $this->_columns->append(new Utility\Column($options));
        return $this;
    }

    /**
     * Adds a foreignKey to the list of foreign keys of this table
     *
     * You can create the ForeignKey object or use an array or standard class
     * with the properties for this foreign key addition
     * 
     * @param ForeignKey|array $options The object or the options to create
     * the object
     *
     * @return \Slick\Database\Query\Ddl\Create A self instance for method
     *  call chains
     */
    public function addForeignKey($options = array())
    {
        if (is_a($options, 'Slick\Database\Query\Ddl\Utility\ForeignKey')) {
            $this->_foreignKeys->append($options);
        } else {
            $this->_foreignKeys->append(new ForeignKey($options));
        }
        return $this;
    }

    /**
     * Adds an index to the current create statement
     * 
     * @param Index|string $column  An Index object or a column name.
     * @param array        $options A property values for Index creation
     *
     * @return \Slick\Database\Query\Ddl\Create A self instance for method
     *  call chains
     *
     * @throws Slick\Database\Exception\InvalidArgumentException If the column
     *  parameter is not an Index object or a column name.
     */
    public function addIndex($column, $options = array())
    {
        if (is_a($column, 'Slick\Database\Query\Ddl\Utility\Index')) {
            $index = $column;
        } else if (is_string($column)) {
            $options = array_merge(
                array(
                    'name' => "{$column}_idx",
                    'indexColumns' => array($column),
                ),
                $options
            );
            $index = new Index($options);
        } else {
            throw new Exception\InvalidArgumentException(
                "To add an indec to a create statement you must use a Index ".
                "object or a column name."
            );
            
        }
        $this->_indexes->append($index);
        return $this;
    }

    /**
     * Add a table option to this create statement
     * 
     * @param string $key   Option name
     * @param string $value Options value
     *
     * @return \Slick\Database\Query\Ddl\Create A self instance for method
     *  call chains
     */
    public function addOption($key, $value)
    {
        $this->_options[$key] = $value;
        return $this;
    }

    /**
     * Executes the create query.
     * 
     * @return boolean True if query was executed successfully
     */
    public function execute()
    {
        return $this->getQuery()
            ->prepareSql($this)
            ->execute();
    }

}