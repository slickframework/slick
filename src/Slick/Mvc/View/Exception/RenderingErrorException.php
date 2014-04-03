<?php

/**
 * RenderingErrorException
 *
 * @package   Slick\Mvc\View\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\View\Exception;

use RuntimeException;
use Slick\Mvc\View\Exception as ViewException;

/**
 * RenderingErrorException
 *
 * @package   Slick\Mvc\View\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RenderingErrorException extends RuntimeException
    implements ViewException
{

} 