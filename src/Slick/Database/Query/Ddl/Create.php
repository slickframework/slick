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
 	public function __construct($options = array())
 	{
 		$this->_columns = new ElementList();
 		$this->_indexes = new ElementList();
 		$this->_foreignKeys = new ElementList();

 		parent::__construct($options);
 	}

}