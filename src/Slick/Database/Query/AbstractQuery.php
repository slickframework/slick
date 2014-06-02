<?php

/**
 * AbstractQuery
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
    Slick\Database\Query\Sql\Transformer,
    Slick\Utility\ArrayMethods,
    Slick\Database\Query\Sql\SqlInterface;
use PDO;

/**
 * AbstractQuery
 *
 * @package   Slick\Database\Query
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractQuery extends Base implements QueryInterface
{

    /**
     * @readwrite
     * @var string The SQL dialect for this query
     */
    protected $_dialect = 'SQLStandard';

    /**
     * @read
     * @var \Slick\Database\Query\Sql\Transformer
     */
    protected $_transformer = null;

    /**
     * @readwrite
     * @var \Slick\Database\Connector\AbstractConnector
     */
    protected $_connector = null;

    /**
     * @readwrite
     * @var SqlInterface
     */
    protected $_sqlStatement = null;

    /**
     * @readwrite
     * @var string|array The query string to perform
     */
    protected $_sql = null;

    /**
     * @readwrite
     * @var boolean Flag for multiple statements
     */
    protected $_multiple = false;

    /**
     * @readwrite
     * @var \PDOStatement The prepared statement to ru
     */
    protected $_preparedStatement = null;

    /**
     * @readwrite
     * @see http://www.php.net/manual/en/pdostatement.fetch.php
     * @var integer The data fetch mode for queries
     */
    protected $_fetchMode = PDO::FETCH_NAMED;

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
     * Set fetch mode for queries
     *
     * @param int $fetchMode PDO fetch mode
     *
     * @return $this
     */
    public function setFetchMode($fetchMode)
    {
        $this->_fetchMode = $fetchMode;
        return $this;
    }

    /**
     * Creates a prepared statement, ready to receive params from given SQL
     *
     * @param string $sql The SQL statement to prepare
     *
     * @throws \Slick\Database\Exception\InvalidSqlException
     * @return $this /PDOStatement A prepared PDOStatement object
     * @see  http://www.php.net/manual/en/class.pdostatement.php
     */
    public function prepare($sql)
    {
        $this->_sql = $sql;
        $this->_multiple = $this->_isMultiple($sql);
        if ($this->_multiple) {
            return $this;
        }

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
        return $this;
    }

    /**
     * Executes current query, binding the provided parameters
     *
     * @param array $params List of parameters to set before execute que query
     *
     * @throws \Slick\Database\Exception\InvalidSqlException
     * @return \Slick\Database\RecordList A record list with the query results
     */
    public function execute($params = array())
    {
        $result = new RecordList();
        try {
            if ($this->_multiple) {
                $this->_multiple = false;
                $queries = $this->_sql;
                foreach ($queries as $sql) {
                    $this->_sql = $sql;
                    $this->_connector->exec($sql);
                }
                
                return  true;
            }

            if ($this->_preparedStatement->execute($params)) {
                if ($this->_preparedStatement->columnCount() > 0) {
                    $result = new RecordList(
                        $this->_preparedStatement->fetchAll($this->_fetchMode)
                    );
                } else {
                    $result = true;
                }
            }
        } catch (\PDOException $exp) {
            $error = "Error executing query: ";
            $error .= $exp->getMessage();
            $error .= ' SQL: '. $this->_sql;
            throw new Exception\InvalidSqlException(
                $error,
                $this->_sql,
                $exp
            );
        }
        return $result;
    }

    /**
     * Returns current SQL transformer
     *
     * @return Transformer
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
     * @param  SqlInterface $sql
     *
     * @return AbstractQuery A self instance for method call chains
     */
    public function prepareSql(SqlInterface $sql)
    {
        $sql = $this->getTransformer()->transform($sql);
        $this->prepare($sql);
        return $this;
    }

    /**
     * Checks if a sql string contains multiple statements
     *
     * @param string $sql The query string
     *
     * @return boolean True if has multiple statements
     */
    protected function _isMultiple($sql)
    {
        $sql = trim(trim($sql, ';'));
        $statements = explode(';', $sql);
        if (sizeof($statements) > 1) {
            $this->_sql = ArrayMethods::trim($statements);
            return true;
        }
        return false;
    }

}