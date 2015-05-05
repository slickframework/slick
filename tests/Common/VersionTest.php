<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Version;

/**
 * Version test case
 *
 * @package Slick\Tests\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class VersionTest extends TestCase 
{

    /**
     * Verify version information
     * @test
     */
    public function checkVersion()
    {
        $this->assertTrue(Version::isLatest());
        $this->assertEquals('-1', Version::compare("0.2.2"));
        $this->assertEquals('0', Version::compare("1.2.0-DEV"));
        $this->assertEquals('1', Version::compare("100.2.2"));
    }
}
