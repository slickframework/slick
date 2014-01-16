<?php

/**
 * Undefined Sql Dialect Exception
 *
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Exception;

use Slick\Database\Exception as DbException;

/**
 * UndefinedSqlDialectException
 *
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UndefinedSqlDialectException extends \RuntimeException
    implements DbException
{

}