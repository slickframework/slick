<?php

/**
 * FlashMessages test case
 *
 * @package   Test\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Mvc\Libs\Session;
use Slick\Configuration\Configuration;
use Slick\Mvc\Libs\Session\FlashMessages;

/**
 * FlashMessages test case
 *
 * @package   Test\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FlashMessagesTest extends \Codeception\TestCase\Test
{
    /**
     * Simple use of flash messages
     * @test
     */
    public function setAndGetMessages()
    {
        Configuration::addPath(
            dirname(dirname(dirname(dirname(__DIR__)))) .
            '/app/Configuration'
        );

        $flm = new FlashMessages();
        $this->assertInstanceOf(
            'Slick\Mvc\Libs\Session\FlashMessages',
            $flm->set(5, 'Test')
        );
        $messages = $flm->get();
        $this->assertTrue(isset($messages[FlashMessages::TYPE_INFO]));
        $this->assertEmpty($flm->get());

        FlashMessages::setMessage(FlashMessages::TYPE_WARNING, "test");
        $expected = [FlashMessages::TYPE_WARNING => ['test']];
        $this->assertEquals($expected, FlashMessages::getMessages());
        FlashMessages::setMessage(FlashMessages::TYPE_WARNING, "test");
        $this->assertEquals($expected, $flm->get());
    }
}