<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Configuration;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Configuration\Configuration;

/**
 * Configuration factory test case
 *
 * @package Slick\Tests\Configuration
 */
class ConfigurationTest extends TestCase
{

    public function testCreateFactory()
    {
        $configuration = new Configuration(
            [
                'type' => Configuration::DRIVER_INI
            ]
        );
        $this->assertInstanceOf(
            'Slick\Configuration\Driver\Ini',
            $configuration->initialize()
        );
    }

    public function testCreateConfigurationFromFile()
    {
        $path = __DIR__ . '/Fixtures/config.php';
        $configuration = new Configuration(
            [
                'type' => Configuration::DRIVER_PHP,
                'file' => $path
            ]
        );
        $configuration = $configuration->initialize();
        $this->assertEquals('Array test', $configuration->get('test'));
    }

    public function testDefaultDriverIsPhp()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf(
            'Slick\Configuration\Driver\Php',
            $configuration->initialize()
        );
    }

    public function testCreateOnTheFly()
    {
        Configuration::addPath(__DIR__ . '/Fixtures');
        $cfg = Configuration::get('config');
        $this->assertEquals('Array test', $cfg->get('test'));
    }

    public function testThrowInvalidClass()
    {
        $this->setExpectedException(
            'Slick\Configuration\Exception\InvalidArgumentException'
        );

        Configuration::get('config', 'test');
    }

    public function testThrowUnknownClass()
    {
        $this->setExpectedException(
            'Slick\Configuration\Exception\InvalidArgumentException'
        );

        Configuration::get('config', 'stdClass');
    }
}
