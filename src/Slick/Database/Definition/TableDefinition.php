<?php

/**
 * TableDefinition
 *
 * @package   Slick\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Definition;

use Slick\Common\Base,
    Slick\Database\Query\Ddl\Utility,
    Slick\Database\Connector\ConnectorInterface,
    Slick\Database\Query\Ddl\Utility\ElementList;

/**
 * ableDefinition
 *
 * @package   Slick\Database\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TableDefinition extends Base
{
    /**
     * @readwrite
     * @var string
     */
    protected $_tableName;

    /**
     * @readwrite
     * @var \Slick\Database\Connector\ConnectorInterface
     */
    protected $_connector;

    /**
     * @read
     * @var \Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_columns;

    /**
     * @readwrite
     * @var Slick\Database\Query\Ddl\Utility\ElementList
     */
    protected $_indexes;

    /**
     * @read
     * @var \Slick\Database\Query\Ddl\UtilityElementList
     */
    protected $_foreignKeys;

    /**
     * @readwrite
     * @var array A list of table options
     */
    protected $_options = array();

    /**
     * @readwrite
     * @var \Slick\Database\RecordList
     */
    protected $_resultSet = null;

    /**
     * @read
     * @var string
     */
    protected $_dialect = 'Standard';

    /**
     * @readwrite
     * @var \Slick\Database\Definition\Parser\ParserInterface
     */
    protected $_parser;

    /**
     * Construct - Set the table name and database connector
     * 
     * @param string $tableName 
     * @param \Slick\Database\Connector\ConnectorInterface $connector
     */
    public function __construct($tableName, ConnectorInterface $connector)
    {
        $options = array(
            'tableName' => $tableName,
            'connector' => $connector
        );
        parent::__construct($options);
    }

    /**
     * Returns the columns of this table definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList A Column list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::Column
     */
    public function getColumns()
    {
        if (
            !is_a(
                $this->_columns,
                'Slick\Database\Query\Ddl\UtilityElementList'
            )
        ) {
            $this->_columns = $this->getParser()->getColumns();
        }
        return $this->_columns;
    }

    /**
     * Returns the indexes of this table definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList An Index list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::Index
     */
    public function getIndexes()
    {
        if (
            !is_a(
                $this->_indexes,
                'Slick\Database\Query\Ddl\UtilityElementList'
            )
        ) {
            $this->_indexes = $this->getParser()->getIndexes();
        }
        return $this->_indexes;
    }

    /**
     * Returns the foreign keys of this table definition
     * 
     * @return \Slick\Database\Query\Ddl\Utility\ElementList A ForeignKey list
     * 
     * @see  Slick\Database\Query\Ddl\Utility::ForeignKey
     */
    public function getForeignKeys()
    {
        if (
            !is_a(
                $this->_indexes,
                'Slick\Database\Query\Ddl\UtilityElementList'
            )
        ) {
            $this->_foreignKeys = $this->getParser()->getForeignKeys();
        }
        return $this->_foreignKeys;
    }

    /**
     * Returns the parser for current dialect
     * 
     * @return \Slick\Database\Definition\Parser\ParserInterface
     */
    public function getParser()
    {
        if (is_null($this->_parser)) {
            $this->_parser = Parser::getParser(
                $this->_dialect,
                $this->_resultSet
            );
        }
        return $this->_parser;
    }

    /**
     * Retrives the information from the database
     */
    public function load()
    {
        $query = $this->connector->ddlQuery();
        $this->_dialect = $query->getDialect();

        $this->_resultSet = $query->definition($this->tableName)->execute();
        return $this;
    }
}