<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Log;

/**
 * Logger factory test case
 *
 * @package Slick\Tests\Common
 */
class LogTest extends TestCase
{

    public function testLoggerFactory()
    {
        $logger = Log::logger('Tests');
        $this->assertInstanceOf('Monolog\Logger', $logger);
    }
}
