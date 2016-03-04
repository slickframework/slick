<?php

/**
 * Controller test case
 *
 * @package   Test\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc\Libs\Session;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Mvc\Libs\Session\FlashMessages;

/**
 * Controller test case
 *
 * @package   Test\Mvc\Libs\Session
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FlashMessagesTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
        parent::_before();
        $path = getcwd() . '/tests/app/Configuration';
        Configuration::addPath($path);
    }

    protected function _after()
    {
        FlashMessages::getMessages();
        parent::_after();
    }

    /**
     * Create Flash messages object
     * @test
     */
    public function createFlashMessagesObject()
    {
        $fm = new FlashMessages();
        $fmStatic = FlashMessages::getInstance();
        //$this->assertEquals($fm, $fmStatic);
        $this->assertNotSame($fm, $fmStatic);

        $this->assertSame($fm->getSession(), $fmStatic->getSession());
    }

    /**
     * Setting messages
     * @test
     */
    public function settingFlashMessages()
    {
        $fm = FlashMessages::setMessage(FlashMessages::TYPE_INFO, 'test');
        $this->assertInstanceOf('Slick\Mvc\Libs\Session\FlashMessages', $fm);
        $fm->set(5, 'other');
        $expected = [
            FlashMessages::TYPE_INFO => [
                'test', 'other'
            ]
        ];
        $this->assertEquals($expected, $fm->get());
        $this->assertEquals([], $fm->get());
    }

}
