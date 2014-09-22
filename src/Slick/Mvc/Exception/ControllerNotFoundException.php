<?php

/**
 * Controller not found Exception
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception;

use RuntimeException;
use Slick\Mvc\Exception;

/**
 * Controller not found Exception
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerNotFoundException extends RuntimeException implements Exception
{

}
