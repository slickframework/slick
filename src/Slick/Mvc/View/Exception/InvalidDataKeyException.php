<?php

/**
 * InvalidDataKeyException
 *
 * @package   Slick\Mvc\View\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\View\Exception;

use LogicException;
use Slick\Mvc\View\Exception as ViewException;

/**
 * InvalidDataKeyException
 *
 * @package   Slick\Mvc\View\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidDataKeyException extends LogicException implements ViewException
{

} 