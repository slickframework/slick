<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Exception;

use BadMethodCallException;
use Slick\Common\Exception;

/**
 * Unimplemented method exception when trying to call a method that does not
 * exists in class
 *
 * @package Slick\Common\Exception
 */
class UnimplementedMethodCallException extends BadMethodCallException
    implements Exception
{

}