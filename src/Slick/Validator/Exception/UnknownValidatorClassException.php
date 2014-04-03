<?php

/**
 * UnknownValidatorClassException
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Validator\Exception;

use RuntimeException;
use Slick\Validator\Exception as ValidatorException;

/**
 * UnknownValidatorClassException
 *
 * @package   Slick\Validator
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class UnknownValidatorClassException extends RuntimeException
    implements ValidatorException
{

} 