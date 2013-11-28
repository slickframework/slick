<?php

/**
 * InvalidArgumentException
 *
 * @package   Slick\Configuration\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Configuration\Exception;

use Slick\Configuration\Exception as CfgException;

/**
 * InvalidArgumentException
 *
 * @package   Slick\Configuration\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidArgumentException extends \RuntimeException
    implements CfgException
{
    
}