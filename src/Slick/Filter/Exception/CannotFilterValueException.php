<?php

/**
 * CannotFilterValueException
 *
 * @package   Slick\Filter\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Filter\Exception;

use RuntimeException;
use Slick\Filter\Exception as FilterException;

/**
 * CannotFilterValueException
 *
 * @package   Slick\Filter\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class CannotFilterValueException extends RuntimeException
    implements FilterException
{

} 