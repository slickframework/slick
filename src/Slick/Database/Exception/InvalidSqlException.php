<?php

/**
 * Invalid Sql Exception
 * 
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Exception;

use Slick\Database\Exception as DatabaseException;

/**
 * Invalid Sql Exception
 *
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidSqlException extends \LogicException
    implements DatabaseException
{

    /**
     * @var string The sql sent to database service.
     */
    public $sql = null;

    /**
     * Overrides the default constructor to set sql and error.
     *  
     * @param string $message The exception error message.
     * @param string $error   The service response error.
     * @param string $sql     The sent SQL string.
     */
    public function __construct($message, $sql, $previous)
    {
        parent::__construct($message, 1, $previous);
        $this->sql = $sql;
    }
}