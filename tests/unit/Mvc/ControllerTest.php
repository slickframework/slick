<?php

/**
 * Controller test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Mvc;

use Slick\Mvc\Controller;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Controller test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ControllerTest extends \Codeception\TestCase\Test
{

    /**
     * Create controller an check the default values
     * @test
     * @expectedException \Slick\Mvc\View\Exception\InvalidDataKeyException
     */
    public function createController()
    {
        $controller = new MyController();
        $this->assertEquals('html', $controller->extension);
        $controller->request = new Request();
        $controller->response = new Response();
        $this->assertInstanceOf(
            'Zend\EventManager\EventManagerInterface',
            $controller->getEventManager()
        );

        $controller->set('foo', 'bar');
        $this->assertEquals('bar', $controller->viewVars['foo']);
        $one = 1; $two = 2;
        $controller->set(compact('one', 'two'));
        $this->assertEquals(2, $controller->viewVars['two']);
        $controller->set(1, 3);
    }

    /**
     * check disable rendering
     * @test
     */
    public function checkDisableRendering()
    {
        $controller = new MyController();
        $this->assertTrue($controller->renderLayout);
        $this->assertTrue($controller->renderView);

        $controller->disableRendering();
        $this->assertFalse($controller->renderLayout);
        $this->assertFalse($controller->renderView);
    }

    /**
     * Check the redirect action on controller
     * @test
     */
    public function controllerRedirect()
    {
        $controller = new MyController();
        $controller->request = new Request();
        $controller->response = new Response();
        $controller->redirect('test');
        $this->assertEquals(302, $controller->response->getStatusCode());

    }

    /**
     * Testing the render method on controller
     * @test
     * @expectedException \Slick\Mvc\View\Exception\RenderingErrorException
     */
    public function controllerRendering()
    {
        $controller = new MyController();
        $layout = $controller->getLayout();
        $this->assertInstanceOf('Slick\Mvc\View', $layout);
        $this->assertEquals('Layouts/default.html.twig', $layout->file);
        $controller->setLayout('testLayout');
        $this->assertEquals('testLayout.html.twig', $controller->getLayout()->file);
        $controller->setView('test');
        $controller->set('foo', 'bar');
        $result = $controller->render();
        $this->assertEquals('<text>bar</text>', $result);
        $controller->renderLayout = true;
        $controller->renderView = true;
        $controller->setView('error');
        $controller->render();
    }

}

class MyController extends Controller
{

}