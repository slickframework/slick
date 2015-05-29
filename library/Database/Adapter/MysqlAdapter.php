<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Adapter;

use PDO;
use Slick\Database\Exception\ServiceException;
use Slick\Database\Sql\Dialect;

/**
 * Mysql database adapter
 *
 * @package Slick\Database\Adapter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $host
 * @property int    $port
 * @property string $database
 * @property string $charset
 * @property string $username
 * @property string $password
 */
class MysqlAdapter extends TransactionalAdapter implements
    AdapterInterface, TransactionsAwareInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $host = 'localhost';

    /**
     * @readwrite
     * @var int
     */
    protected $port = 3306;
    /**
     * @readwrite
     * @var string
     */
    protected $database;

    /**
     * @readwrite
     * @var string
     */
    protected $charset = 'utf8';

    /**
     * @readwrite
     * @var string
     */
    protected $username;

    /**
     * @readwrite
     * @var string
     */
    protected $password;

    /**
     * @read
     * @var string
     */
    protected $dialect = Dialect::MYSQL;

    /**
     * Connects to the database service
     *
     * @return AbstractAdapter The current adapter to chain method calls
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     */
    public function connect()
    {
        $dsn = "mysql:host={$this->host};port={$this->port};".
            "dbname={$this->database};charset={$this->charset}";
        $className = $this->handleClassName;
        try {

            $this->handler = new $className(
                $dsn,
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->handler->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
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
        return $this->database;
    }
}