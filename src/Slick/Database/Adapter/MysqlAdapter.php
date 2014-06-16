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
    protected $_database = 'test';

    /**
     * @readwrite
     * @var string
     */
    protected $_charset = 'utf-8';

    /**
     * @readwrite
     * @var string
     */
    protected $_username = 'root';

    /**
     * @readwrite
     * @var string
     */
    protected $_password = '';

    /**
     * Connects to the database service
     *
     * @throws ServiceException If any error occurs while trying to
     *  connect to the database service
     * @return AdapterInterface The current adapter to chain method calls
     */
    public function connect()
    {
        $dsn = $dsn = "mysql:host={$this->_host};port={$this->_port}" .
            "dbname={$this->_database};charset={$this->_charset}";
        try {
            $this->_handler = new PDO(
                $dsn,
                $this->_username,
                $this->_password,
                [PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $exp) {
            throw new ServiceException(
                "An error occurred when trying to connect to database " .
                "service. Error: {$exp->getMessage()}"
            );
        }
    }

}