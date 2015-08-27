<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Configuration\Exception;

use RuntimeException;
use Slick\Configuration\Exception;

/**
 * File Not Found Exception, trowed when trying to load a file that
 * does not exists.
 *
 * @package Slick\Configuration\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class FileNotFoundException extends RuntimeException implements Exception
{

}
