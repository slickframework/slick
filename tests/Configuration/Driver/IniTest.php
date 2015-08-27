<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Configuration\Driver;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Configuration\Driver\Ini;

/**
 * INI configuration driver test case
 *
 * @package Slick\Tests\Configuration\Driver
 */
class IniTest extends TestCase
{

    /**
     * @var Ini
     */
    protected $driver;

    /**
     * Create the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = new Ini(
            [
                'file' => dirname(__DIR__) . '/Fixtures/config.ini'
            ]
        );
    }

    /**
     * Cleanup for next test
     */
    protected function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }

    /**
     * Should get the value form file
     * @test
     */
    public function getAValue()
    {
        $expected = 'Ini test';
        $this->assertEquals($expected, $this->driver->get('test', false));
    }

    /**
     * Should throw an parser error exception
     * @test
     */
    public function loadFileWithError()
    {
        $this->setExpectedException(
            'Slick\Configuration\Exception\ParserErrorException'
        );
        $this->driver->setFile(dirname(__DIR__) . '/Fixtures/bad-config.ini');
        $this->driver->set('hello', 'world');
    }
}
