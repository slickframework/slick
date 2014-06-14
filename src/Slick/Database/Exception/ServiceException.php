<?php

/**
 * Service exception
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Exception;

use RuntimeException;
use Slick\Database\Exception;

/**
 * Database service related and runtime errors
 *
 * @package   Slick\Database\Adapter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ServiceException extends RuntimeException implements Exception
{

} 