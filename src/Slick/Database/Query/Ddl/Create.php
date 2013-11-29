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
    Slick\Database\Query\QueryInterface;

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
     * @var Slick\Database\Query\Ddl\Utility
     */
    protected $_columns;

    /**
     * @read
     * @var Slick\Database\Query\Ddl\Utility
     */
    protected $_indexes;

    /**
     * @read
     * @var Slick\Database\Query\Ddl\Utility
     */
    protected $_foreignKeys;


    /**
     * Overrides default constructor to initilize the table elements lists
     * 
     * @param array|object $options The properties for the object
     *  beeing constructed.
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

    public function addForeignKey($options = array())
    {

    }

}