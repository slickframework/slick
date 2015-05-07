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
 * SQL query exception thrown when an error
 *
 * @package Slick\Database\Exception
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class SqlQueryException extends LogicException implements Exception
{

}