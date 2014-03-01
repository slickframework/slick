<?php

/**
 * UnknownElementException
 *
 * @package   Slick\Form\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Exception;

use LogicException;
use Slick\Form\Exception as FormException;

/**
 * UnknownElementException
 *
 * @package   Slick\Form\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UnknownElementException extends LogicException implements FormException
{

} 