<?php

/**
 * This file is part of slick/configuration package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Configuration\Driver;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Configuration\Driver\Php;

/**
 * PHP arrays configuration driver test case
 *
 * @package Slick\Tests\Configuration\Driver
 */
class PhpTest extends TestCase
{

    /**
     * @var Php
     */
    protected $driver;

    /**
     * Create the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = new Php(
            [
                'file' => dirname(__DIR__) . '/Fixtures/config.php'
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
     * Should set the value overriding the on loaded from file
     * @test
     */
    public function setAValue()
    {
        $expected = 'test 2';
        $this->driver->set('test', $expected);
        $this->assertEquals($expected, $this->driver->get('test', false));
    }

    /**
     * Should throw a file not found exception
     * @test
     */
    public function setInvalidFile()
    {
        $this->setExpectedException(
            'Slick\Configuration\Exception\FileNotFoundException'
        );
        $this->driver->setFile('foo');
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
        $this->driver->setFile(dirname(__DIR__) . '/Fixtures/bad-config.php');
        $this->driver->set('hello', 'world');
    }
}
