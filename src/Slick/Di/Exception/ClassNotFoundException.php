<?php

/**
 * ClassNotFoundException
 *
 * @package   Slick\Di\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Exception;

use Slick\Di\Exception as DiException;
use RuntimeException;

/**
 * ClassNotFoundException
 *
 * @package   Slick\Di\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ClassNotFoundException extends DiException
    implements RuntimeException
{

}