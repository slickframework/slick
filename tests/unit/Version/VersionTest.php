<?php

/**
 * Version test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Version;

use Codeception\Util\Stub;
use Slick\Version\Version;

/**
 * Version test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class VersionTest extends \Codeception\TestCase\Test
{

    /**
     * Verify version information
     * @test
     */
    public function checkVersion()
    {
        $this->assertTrue(Version::isLatest());
        $this->assertEquals('-1', Version::compare("0.2.2"));
        $this->assertEquals('0', Version::compare("1.0.0-alpha"));
        $this->assertEquals('1', Version::compare("100.2.2"));
    }
}