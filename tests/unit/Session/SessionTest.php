<?php

/**
 * Session test case
 * 
 * @package   Test\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Session;

use Codeception\Util\Stub;
use Slick\Session\Session,
    Slick\Session\Driver,
    Slick\Common\Base;

/**
 * Session test case
 */
class SessionTest extends \Codeception\TestCase\Test
{

    /**
     * Using session to obtain a sassion driver
     * @test
     * @expectedException Slick\Session\Exception\InvalidArgumentException
     * @expectedExceptionMessage he session type 'test' isn't implemented.
     */
    public function initializeASessionDriver()
    {
        $session = new Session(
            array(
                'class' => 'server'
            )
        );

        $driver = $session->initialize();
        $this->assertInstanceOf('\Slick\Session\Driver\DriverInterface', $driver);
        $this->assertInstanceOf('Slick\Session\Driver\Driver', $driver);
        $this->assertInstanceOf('Slick\Session\Driver\Server', $driver);

        $session->setClass('test');
        $session->initialize();
    }

    /**
     * Initializing an empty session
     * @test
     * @expectedException Slick\Session\Exception\InvalidArgumentException
     * @expectedExceptionMessage The session driver is invalid.
     */
    public function initializeEmptyDriver()
    {
        $session = new Session();
        $session->setClass(null);
        $session->initialize();
    }

    /**
     * Initializing a driver given a driver class name
     * @test
     * @expectedException Slick\Session\Exception\InvalidArgumentException
     * @expectedExceptionMessage The session type '\Session\CrazyDriver' doesn't inherited from Slick\Driver\DriverInterface.
     */
    public function initializeDriverFromClassName()
    {
        $session = new Session(array('class' => '\Session\CostumDriver'));
        $driver = $session->initialize();
        $this->assertInstanceOf('Slick\Session\Driver\Server', $driver);

        $session->setClass('\Session\CrazyDriver');
        $session->initialize();
    }

}

/**
 * Wrong session driver
 */
class CrazyDriver extends Base
{

}

/**
 * A good driver class
 */
class CostumDriver extends Driver\Server
{

}