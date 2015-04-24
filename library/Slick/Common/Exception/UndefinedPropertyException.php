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
 * Undefined Property Exception thrown when trying to assign a value to
 * an un-existing property
 *
 * @package Slick\Common\Exception
 */
class UndefinedPropertyException extends LogicException implements Exception
{

}