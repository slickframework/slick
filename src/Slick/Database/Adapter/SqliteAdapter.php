<?php

/**
 * Sqlite database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Adapter;

use PDO;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\Exception\ServiceException;

/**
 * Sqlite database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $file Database file name
 * @method SqliteAdapter setFile(string $file) Set the database file name
 * @method string getFile() Returns the current database file name
 */
class SqliteAdapter extends AbstractAdapter implements AdapterInterface
{

    /**
     * @readwrite
     * @var string Database file name
     */
    protected $_file = ':memory:';

    /**
     * @write
     * @var string
     */
    protected $_dialect = Dialect::SQLITE;

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     * connect to the database service
     * @return SqliteAdapter The current adapter to chain method calls
     */
    public function connect()
    {
        $dsn = "sqlite:{$this->_file}";
        try {
            $class = $this->_handlerClass;
            $this->_handler = new $class($dsn);
            $this->_handler->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->_connected = true;
        } catch (\Exception $exp) {
            throw new ServiceException(
                "An error occurred when trying to connect to database " .
                "service. Error: {$exp->getMessage()}"
            );
        }
        return $this;
    }

    /**
     * Returns the schema name for this adapter
     *
     * @return string
     */
    public function getSchemaName()
    {
        return null;
    }

    /**
     * Executes an SQL or DDL query and returns the number of affected rows
     *
     * @param string|SqlInterface $sql A string containing
     * the SQL query to perform or the equivalent
     * {@see \Slick\Database\Sql\SqlInterface} object
     * @param array $parameters A list of parameters to bind
     * {@see http://php.net/manual/en/pdo.prepare.php PDO prepared statements}
     *
     * @throws \Slick\Database\Exception\InvalidArgumentException if the
     * sql provided is not a string or does not implements the
     * {@see \Slick\Database\Sql\SqlInterface} interface
     *
     * @throws SqlQueryException If any error occurs while preparing or
     * executing the SQL query
     *
     * @return integer The number of affected rows by executing the query
     */
    public function execute($sql, $parameters = [])
    {
        $sql = ($sql instanceof SqlInterface) ? $sql->getQueryString(): $sql;
        if (strpos($sql, ';') <= 0) {
            return parent::execute($sql, $parameters);
        }

        $parts = explode(';', $sql);
        $result = 0;
        foreach ($parts as $query) {
            $result = parent::execute($query, $parameters = []);
        }
        return $result;
    }
}
