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

/**
 * Invalid Sql Exception
 *
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidSqlException extends \LogicException
	implements ExceptionInterface
{

	/**
	 * @var string The sql sent to database service.
	 */
	public $sql = null;

	/**
	 * @var string The service response error.
	 */
	public $error = null;

	/**
	 * Overrides the default constructor to set sql and error.
	 *  
	 * @param string $message The exception error message.
	 * @param string $error   The service response error.
	 * @param string $sql     The sent SQL string.
	 */
	public function __construct($message, $error, $sql)
	{
		parent::__construct($message);
		$this->sql = $sql;
		$this->error = $error;
	}
}