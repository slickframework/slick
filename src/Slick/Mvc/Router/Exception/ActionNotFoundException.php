<?php

/**
 * ActionNotFoundException
 *
 * @package   Slick\Mvc\Router\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Router\Exception;

use RuntimeException;
use Slick\Mvc\Router\Exception as RouterException;

/**
 * ActionNotFoundException
 *
 * @package   Slick\Mvc\Router\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ActionNotFoundException extends RuntimeException
    implements RouterException
{

} 