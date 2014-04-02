<?php

/**
 * Exception handler
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Exception;

use Slick\Common\Base;
use Exception;
use Slick\Mvc\Exception\Handlers\DefaultHandler;

/**
 * Exception handler (Handles uncaught exceptions)
 *
 * @package   Slick\Mvc\Exception
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Handler extends Base
{

    protected $_debugMode = 0;

    /**
     * Handles the exception
     *
     * @param Exception $exp
     */
    public static function handle(Exception $exp)
    {
        $handler = new DefaultHandler(['exception' => $exp]);
        $handler->getResponse()->send();
    }

} 