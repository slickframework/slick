<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Exception;

use RuntimeException;
use Slick\Database\Exception;

/**
 * Exception thrown when trying to rollback a transaction but there is
 * no active transactions.
 *
 * @package Slick\Database\Exception
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class NoActiveTransactionException extends RuntimeException implements
    Exception
{

}