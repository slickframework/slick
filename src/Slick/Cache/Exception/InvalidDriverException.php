<?php

/**
 * InvalidDriverException
 *
 * @package   Slick\Cache\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Cache\Exception;

use Slick\Cache\Exception as CacheException;

/**
 * Used when factory does not recognises the requested cache driver.
 *
 * @package   Slick\Cache\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidDriverException extends \RuntimeException
    implements CacheException
{

}