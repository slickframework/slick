<?php

/**
 * Read Only Exception
 * 
 * @package   Slick\Common\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Common\Exception;

use Slick\Common\Exception as CommonException;

/**
 * ReadOnlyException
 *
 * @package   Slick\Common\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ReadOnlyException extends \LogicException
    implements CommonException
{
    
}

