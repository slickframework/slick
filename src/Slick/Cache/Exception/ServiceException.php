<?php

/**
 * ServiceException
 *
 * @package   Slick\Cache\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Exception;

use Slick\Cache\Exception as CacheException;
use RuntimeException;

/**
 * Used when an error occurs trying to use a cache service
 *
 * @package   Slick\Cache\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ServiceException extends RuntimeException implements CacheException
{
    
}