<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Exception;

use LogicException;
use Slick\Common\Exception;

/**
 * Write Only Exception thrown when trying to assign a value to a property
 * that was defined with @write annotation
 *
 * @package Slick\Common\Exception
 */
class WriteOnlyException extends LogicException implements Exception
{

}