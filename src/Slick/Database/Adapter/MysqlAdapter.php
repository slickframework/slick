<?php

/**
 * Mysql database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Adapter;

use Slick\Database\Exception\ServiceException;
use PDO;
use Slick\Database\Sql\Dialect;

/**
 * Mysql database adapter
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class MysqlAdapter extends AbstractAdapter implements AdapterInterface
{

    /**
     * @readwrite
     * @var string
     */
    protected $_host = 'localhost';

    /**
     * @readwrite
     * @var string
     */
    protected $_port = 3306;

    /**
     * @readwrite
     * @var string
     */
    protected $_database;

    /**
     * @readwrite
     * @var string
     */
    protected $_charset = 'utf8';

    /**
     * @readwrite
     * @var string
     */
    protected $_username;

    /**
     * @readwrite
     * @var string
     */
    protected $_password;

    /**
     * @var string
     */
    protected $_dialect = Dialect::MYSQL;

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function connect()
    {
        $dsn = "mysql:host={$this->_host};port={$this->_port}" .
            "dbname={$this->_database};charset={$this->_charset}";
        try {
            $class = $this->_handlerClass;
            $this->_handler = new $class(
                $dsn,
                $this->_username,
                $this->_password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->_handler->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
            $this->_connected = true;
        } catch (\Exception $exp) {
            throw new ServiceException(
                "An error occurred when trying to connect to database " .
                "service. Error: {$exp->getMessage()}"
            );
        }
    }

}