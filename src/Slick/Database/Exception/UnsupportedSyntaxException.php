<?php

/**
 * Unsupported Syntax Exception
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
 * UnsupportedSyntaxException
 *
 * @package   Slick\Database\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UnsupportedSyntaxException extends \RuntimeException
    implements DbException
{

}