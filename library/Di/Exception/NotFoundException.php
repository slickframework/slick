<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Di\Exception;

use Interop\Container\Exception\NotFoundException as IteropExp;

/**
 * Not Found Exception thrown when trying to get an entry that was not
 * registered before it.
 *
 * @package Slick\Di\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class NotFoundException extends InvalidArgumentException implements IteropExp
{

}