<?php

/**
 * Controller test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Slick\Mvc\Controller;
use Codeception\TestCase\Test;
use Slick\Mvc\Libs\Session\FlashMessages;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class ControllerTest
 * @package Mvc
 */
class ControllerTest extends Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Assign/retrieve/erase values to render
     * @test
     * @expectedException \Slick\Mvc\Exception\InvalidArgumentException
     */
    public function addingValuesToRender()
    {
        $controller = new Controller();
        $this->assertNull($controller->get('key', null));
        $this->assertInstanceOf('Slick\Mvc\Controller', $controller->set('key', 'value'));
        $this->assertInstanceOf('Slick\Mvc\Controller', $controller->set([
            'one' => 1,
            'two' => 2
        ]));
        $this->assertInstanceOf('Slick\Mvc\Controller', $controller->erase('two'));
        $this->assertEquals(['key' => 'value', 'one' => 1], $controller->getViewVars());
        $this->assertEquals(1, $controller->get('one'));
        $controller->set(true, false);
        $this->assertInstanceOf('Slick\Mvc\Controller', $controller->disableRendering());
        $this->assertFalse($controller->renderLayout);
        $this->assertFalse($controller->renderView);
    }

    /**
     * Redirect controller flow
     * @test
     */
    public function redirectFlow()
    {

        $controller = new Controller(
            [
                'response' => new Response(),
                'request' => new Request(),
            ]
        );
        $controller->redirect('home');
        $this->assertEquals(302, $controller->getResponse()->getStatusCode());
        $headers = $controller->getResponse()->getHeaders();
        $this->assertEquals('/home', $headers->get('Location')->getFieldValue());
    }

    /**
     * Settings flash messages
     * @test
     */
    public function addingFlashMessages()
    {
        $controller = new Controller();
        $fm = $controller->flashMessages;
        $controller->addInfoMessage('Test info')
            ->addSuccessMessage("Test success")
            ->addErrorMessage('Test error')
            ->addWarningMessage('Test warning')
            ->setMessage(FlashMessages::TYPE_INFO, 'Other');
        $this->assertEquals(
            [
                FlashMessages::TYPE_INFO => ['Test info', 'Other'],
                FlashMessages::TYPE_SUCCESS => ['Test success'],
                FlashMessages::TYPE_ERROR => ['Test error'],
                FlashMessages::TYPE_WARNING => ['Test warning'],
            ],
            $fm->get()
        );
        $fm->flush();

    }

}
