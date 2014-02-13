<?php

/**
 * InvalidArgumentException
 *
 * @package   Slick\Template\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Template\Exception;

use Slick\Template\Exception as TemplateException;
use RuntimeException;

/**
 * InvalidArgumentException
 *
 * @package   Slick\Template\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends RuntimeException
    implements TemplateException
{
    
}