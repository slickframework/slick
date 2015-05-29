<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

use PDO;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Exception\SqlQueryException;
use Slick\Database\Sql\Dialect;
use Slick\Database\Sql\SqlInterface;

/**
 * Sqlite database adapter
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SqliteAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * @readwrite
     * @var string Database file name
     */
    protected $file = ':memory:';
    /**
     * @write
     * @var string
     */
    protected $dialect = Dialect::SQLITE;
    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     * connect to the database service
     * @return SqliteAdapter The current adapter to chain method calls
     */
    public function connect()
    {
        $dsn = "sqlite:{$this->file}";
        $class = $this->handleClassName;
        try {
            $this->handler = new $class($dsn);
            $this->handler->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            $this->connected = true;
        } catch (\Exception $exp) {
            throw new ServiceException(
                "An error occurred when trying to connect to database ".
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
        $sql = $this->getSql($sql);

        $parts = explode(';', $sql);
        $result = 0;
        foreach ($parts as $query) {
            $result = parent::execute($query, $parameters = []);
        }
        return $result;
    }
}