<?php

/**
 * Invalid Argument Exception
 *
 * @package   Slick\Common\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Exception;

use Slick\Common\Exception as CommonException;

/**
 * InvalidArgumentException
 *
 * @package   Slick\Common\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends \RuntimeException
    implements CommonException
{

}