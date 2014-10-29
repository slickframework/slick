<?php

/**
 * Scaffold controller test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use CodeGuy;
use Codeception\Util\Stub;
use Codeception\TestCase\Test;
use Slick\Mvc\Controller;
use Slick\Mvc\Dispatcher;
use Slick\Mvc\Scaffold;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Scaffold controller test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ScaffoldTest extends Test
{
   /**
    * @var CodeGuy
    */
    protected $codeGuy;

    /**
     * Trying to create a scaffold controller
     * @test
     */
    public function createScaffoldController()
    {
        $routeInfo = Stub::make(
            'Slick\Mvc\Router\RouteInfo',
            [
                'getController' => function() {
                    return 'Mvc\MyScaffoldController';
                }
            ]
        );
        $application = Stub::make(
            'Slick\Mvc\Application',
            [
                'getRequest' => function() {return new Request(); },
                'getResponse' => function() {return new Response(); }
            ]
        );
        $dispatcher = new Dispatcher(
            [
                'routeInfo' => $routeInfo,
                'application' => $application
            ]
        );
        /** @var Scaffold $scaffold */
        $scaffold = $dispatcher->getController('index');
        $this->assertInstanceOf('Slick\Mvc\Scaffold', $scaffold);
        $this->assertInstanceOf(
            'Mvc\MyScaffoldController',
            $scaffold->getController()
        );

        $this->assertEquals('Models\MyScaffoldController', $scaffold->modelName);
        $this->assertEquals('myScaffoldControllers', $scaffold->get('modelPlural'));
        $this->assertEquals('myScaffoldController', $scaffold->get('modelSingular'));
        $scaffold->setModelName("Models\\User");
        $this->assertEquals('users', $scaffold->get('modelPlural'));
        $this->assertEquals('user', $scaffold->get('modelSingular'));
    }

}

/**
 * Mock controller for test
 *
 * @package Mvc
 */
class MyScaffoldController extends Controller
{
    /**
     * @readwrite
     * @var bool
     */
    protected $_scaffold = true;
}
