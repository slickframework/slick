<?php

/**
 * Invalid argument exception
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Exception;

use LogicException;
use Slick\Database\Exception;

/**
 * Invalid argument exception
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends LogicException implements Exception
{

} 