<?php

/**
 * Session driver test case
 * 
 * @package   Test\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Session\Driver;

use Slick\Session\Session;

/**
 * Session driver test case
 *
 * @package   Test\Session\Driver
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
class DriverTest extends \Codeception\TestCase\Test
{

    /**
     * @var \Slick\Session\Driver\Server The SUT object.
     */
    protected $_server = null;

    /**
     * Initialize a SUT session driver
     */
    protected function _before()
    {
        parent::_before();
        $session = new Session();
        $this->_server = $session->Initialize();
        unset($session);
    }

    /**
     * Clean up for next test.
     */
    protected function _after()
    {
        unset($this->_server);
        parent::_after();
    }

    /**
     * Set, get and erase values on session
     * @test
     */
    public function usingValuesOnSession()
    {
        $obj = $this->_server->set('test', 'My test value');
        $this->assertInstanceOf('Slick\Session\Driver\Server', $obj);

        $this->assertEquals('My test value', $_SESSION['slick_test']);
        $this->assertEquals(
            $_SESSION['slick_test'],
            $this->_server->get('test')
        );

        $this->assertFalse($this->_server->get('other', false));
        $this->assertFalse(
            $this->_server->set('other', 'Another test')
                ->erase('other')
                ->get('other', false)
        );
    }

}