<?php

/**
 * Null Poiter Exception
 * 
 * @package   Slick\Utility\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility\Exception;

use Slick\Utility\Exception as UtilityException;

/**
 * NullPointerException
 *
 * @package   Slick\Utility\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class NullPointerException extends \RuntimeException
    implements UtilityException
{
    
}