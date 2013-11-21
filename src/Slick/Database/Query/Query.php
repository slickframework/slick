<?php

/**
 * Query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query;

use Slick\Common\Base,
    Slick\Database\RecordList,
    Slick\Database\Exception,
    Slick\Database\Query\Sql,
    Slick\Database\Query\Sql\Transformer;

/**
 * Query represents a database query
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Query extends Base implements QueryInterface
{

    /**
     * @readwrite
     * @var string The SQL dialect for this query
     */
    protected $_dialect = 'SQLtandard';

    /**
     * @read
     * @var Slick\Database\Query\Sql\Transformer
     */
    protected $_transformer = null;

    /**
     * @readwrite
     * @var \Slick\Database\Connector\AbstractConnector
     */
    protected $_connector = null;

    /**
     * @readwrite
     * @var \Slick\Database\Query\SqlInterface
     */
    protected $_sqlStatement = null;

    /**
     * @readwrite
     * @var string The query string to perform
     */
    protected $_sql = null;

    /**
     * @readwrite
     * @var /PDOStatement The prepared statement to ru
     */
    protected $_preparedStatement = null;

    /**
     * @see http://www.php.net/manual/en/pdostatement.fetch.php 
     * @var integer The data fetch mode for queries
     */
    protected $_fetchMode = \PDO::FETCH_OBJ;

    /**
     * Overrides base constructor to check sql presence.
     *
     * If the query is constructed with a provided sql query string it will
     * prepare that string and set the PDOStatement object ready to be
     * executed
     * 
     * @param array $options A list of name/value pairs for object
     * initialization
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        if (!is_null($this->_sql)) {
            $this->_preparedStatement = $this->_connector
                ->prepare($this->_sql);
        }
    }

    /**
     * Creates a 'Select' SQL statement
     * 
     * @param string $tableName The table name for select statement
     * @param array  $fields    A list of fields to retreive whit query
     * 
     * @return \Slick\Database\Query\Sql\Select The SQL select object
     */
    public function select($tableName, $fields = array('*'))
    {
        $this->_sqlStatement = new Sql\Select($tableName, $fields, $this);
        return $this->_sqlStatement;
    }

    /**
     * Creates a 'Insert' SQL statement
     * 
     * @param string $tableName The table name for insert statement
     * @return \Slick\Database\Query\Sql\Insert The SQL insert object
     */
    public function insert($tableName)
    {
        $this->_sqlStatement = new Sql\Insert($tableName, $this);
        return $this->_sqlStatement;
    }

    /**
     * Creates a prepared statement, ready to receive params from given SQL
     * 
     * @param string $sql The SQL statement to prepare
     * 
     * @return /PDOStatement A prepared PDOStatement object
     * @see  http://www.php.net/manual/en/class.pdostatement.php
     */
    public function prepare($sql)
    {
        $this->_sql = $sql;
        try {
            $this->_preparedStatement = $this->_connector
                ->prepare($sql);
        } catch (\PDOException $exp) {
            $message = $exp->getMessage();
            throw new Exception\InvalidSqlException(
                "Error preparing SQL statement: {$message}",
                $sql,
                $exp
            );
            
        }
        return $this->_preparedStatement;
    }

    /**
     * Executes current query, binding the provided parameters
     * 
     * @param array $params List of parameters to set before execute que query
     * 
     * @return \Slick\Database\RecordList A record list with the query results
     */
    public function execute($params = array())
    {
        $result = new RecordList();
        if ($this->_preparedStatement->execute($params))
            $result = new RecordList($this->_preparedStatement->fetchAll($this->_fetchMode));
        return $result;
    }

    /**
     * Returns current SQL transformer
     * 
     * @return Slick\Database\Query\Sql\Transformer
     */
    public function getTransformer()
    {
        if (is_null($this->_transformer)) {
            $this->_transformer = Transformer::create($this->_dialect);
        }
        return $this->_transformer;
    }

    /**
     * Prepares a PDOStatement based on a provided SQL Statement object.
     * 
     * @param  \Slick\Database\Query\Sql\SqlInterface $sql
     * 
     * @return \Slick\Database\Query\Query A self instance for method
     * call chains
     */
    public function prepareSql(\Slick\Database\Query\Sql\SqlInterface $sql)
    {
        $sql = $this->getTransformer()->transform($sql);
        $this->prepare($sql);
        return $this;
    }
}