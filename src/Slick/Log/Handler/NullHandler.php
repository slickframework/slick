<?php

/**
 * Default Logger handler
 *
 * @package   Slick\Log\Handler
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Log\Handler;

use Monolog\Logger;
use Monolog\Handler\NullHandler as BaseHandler;

/**
 * Default Logger handler
 *
 * @package   Slick\Log\Handler
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class NullHandler extends BaseHandler
{

    /**
     * @param integer $level The minimum logging level at which this handler
     * will be triggered
     */
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level, true);
    }
}
