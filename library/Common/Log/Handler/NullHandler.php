<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Common\Log\Handler;

use Monolog\Handler\NullHandler as BaseHandler;
use Monolog\Logger;

/**
 * Class NullHandler
 *
 * @package Slick\Common\Log\Handler
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class NullHandler extends BaseHandler
{
    /**
     * @param integer $level The minimum logging level at which this handler
     * will be triggered
     */
    public function __construct($level = Logger::DEBUG)
    {
        parent::__construct($level, false);
    }
}