<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Exception;

use LogicException;
use Slick\Database\Exception;

/**
 * Invalid argument usage exception thrown in slick/common package
 *
 * @package Slick\Database\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends LogicException implements Exception
{

}