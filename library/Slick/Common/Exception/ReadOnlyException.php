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
 * Read Only Exception thrown when trying to get the value of a property
 * that was defined with @read annotation
 *
 * @package Slick\Common\Exception
 */
class ReadOnlyException extends LogicException implements Exception
{

}