<?php

/**
 * Router test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */
namespace Mvc;
use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Configuration\Driver\DriverInterface;
use Slick\Mvc\Application;
use Slick\Mvc\Controller;
use Slick\Mvc\Router;
use Zend\Http\PhpEnvironment\Request;

/**
 * Router test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RouterTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * SUT router object
     * @var Router
     */
    protected $_router;

    /**
     * Configuration driver
     * @var DriverInterface
     */
    protected $_config;

    /**
     *  Creates the object fot the test
     */
    protected function _before()
    {
        parent::_before();
        $this->_router = new Router();
        if (is_null($this->_config)) {
            Configuration::addPath(dirname(dirname(__DIR__)).'/app/Configuration');
            Configuration::addPath(__DIR__);
            $cfg = Configuration::get('routerConfig');
            $this->_router->setConfiguration($cfg);
            $this->_config = $cfg;
        }

    }

    /**
     * Clear everything for next test
     */
    protected function _after()
    {
        unset($this->_router);
        parent::_after();
    }

    /**
     * ManageRoutes
     * @test
     */
    public function manageRoutes()
    {
        $this->assertSame($this->_config, $this->_router->getConfiguration());
        $this->_router->request = new Request();
        $this->assertEquals('html', $this->_router->getExtension());
        $router = new Router();
        $this->assertEquals('home', $router->getConfiguration()->get('router.action'));

        $_GET['extension'] = 'json';
        $this->_router->request = new Request();
        $this->_router->extension = null;
        $this->assertEquals('json', $this->_router->getExtension());

        $route = new Router\Route\Simple([
            'pattern' => ':controller/:id/:action'
        ]);
        $this->_router->addRoute($route);
        $_GET['url'] = 'users/2/view';
        $this->_router->request = new Request();
        $filtered = $this->_router->filter();
        $this->assertInstanceOf('Slick\Mvc\Router', $filtered);
        $this->assertEquals('users', $filtered->controller);
        $this->assertEquals('view', $filtered->action);
        $this->assertEquals(['id' => 2], $filtered->params);

        $_GET['url'] = 'users/edit/1/full';
        $this->_router->request = new Request();
        $filtered = $this->_router->filter();
        $this->assertEquals('users', $filtered->controller);
        $this->assertEquals('edit', $filtered->action);
        $this->assertEquals([1, 'full'], $filtered->params);

        $route2 = new Router\Route\Simple([
            'pattern' => 'api/:controller/:action/:params'
        ]);
        $this->_router->addRoute($route2);
        $expected =  array(
            ":controller/:id/:action" => 'Slick\Mvc\Router\Route\Simple',
            "api/:controller/:action/:params" => 'Slick\Mvc\Router\Route\Simple'
        );
        $this->assertEquals($expected, $this->_router->getRoutes());
        $this->_router->removeRoute($route2);

        $expected =  array(
            ":controller/:id/:action" => 'Slick\Mvc\Router\Route\Simple',
        );
        $this->assertEquals($expected, $this->_router->getRoutes());
    }

    /**
     * Dispatch a request
     * @test
     */
    public function dispatch()
    {
        $app = new Application();
        unset($_GET['url']);
        $this->_router->request = new Request();
        $router = $this->_router->filter();
        $router->dispatch($app);
        $this->assertEquals('-1', MyPages::$call);
    }

    /**
     * Check the call for an unknown controller
     * @test
     * @expectedException \Slick\Mvc\Router\Exception\ControllerNotFoundException
     */
    public function callUnknownController()
    {
        $app = new Application();
        $_GET['url'] = 'hello/world';
        $this->_router->request = new Request();
        $router = $this->_router->filter();
        $router->dispatch($app);
    }

    /**
     * Check the call to a private action
     * @test
     * @expectedException \Slick\Mvc\Router\Exception\ActionNotFoundException
     */
    public function callUnknownAction()
    {
        $app = new Application();
        $_GET['url'] = 'myPages/world';
        $this->_router->request = new Request();
        $router = $this->_router->filter();
        $router->dispatch($app);
    }

    /**
     * Check the call to a private action
     * @test
     * @expectedException \Slick\Mvc\Router\Exception\ActionNotFoundException
     */
    public function callProtectedAction()
    {
        $app = new Application();
        $_GET['url'] = 'myPages/callMe';
        $this->_router->request = new Request();
        $router = $this->_router->filter();
        $router->dispatch($app);
    }

    /**
     * Checking @before, @after and @once callbacks
     * @test
     */
    public function checkCallbacks()
    {
        MyPages::$foo = 0;
        MyPages::$bar = 0;

        $app = new Application();
        $_GET['url'] = 'myPages/someAction';
        $this->_router->request = new Request();
        $router = $this->_router->filter();
        $router->dispatch($app);

        $this->assertEquals(1, MyPages::$foo);
        $this->assertEquals(2, MyPages::$bar);
    }

    /**
     * check simple route match
     * @test
     */
    public function checkSimpleMatch()
    {
        $route = new Router\Route\Simple([
            'pattern' => 'about',
            'controller' => 'pages',
            'action' => 'about'
        ]);
        $this->_router->addRoute($route);
        $_GET['url'] = "about";
        $this->_router->request = new Request();
        $filtered = $this->_router->filter();
        $this->assertEquals('pages', $filtered->controller);
        $this->assertEquals('about', $filtered->action);
    }

    /**
     * Check the use of regex route
     * @test
     */
    public function checkRegexRoute()
    {
        $route = new Router\Route\Regex([
            'pattern' => '\/?([a-z\-\_]+)\/(\d*)\/([a-z\-\_]+)\/?.*',
            'keys' => [
                'controller', 'id', 'action'
            ]
        ]);
        $this->_router->addRoute($route);
        $_GET['url'] = "members/23/update";
        $this->_router->request = new Request();
        $filtered = $this->_router->filter();
        $this->assertEquals('members', $filtered->controller);
        $this->assertEquals('update', $filtered->action);

        $_GET['url'] = "members/read/23";
        $this->_router->request = new Request();
        $filtered = $this->_router->filter();
        $this->assertEquals('members', $filtered->controller);
        $this->assertEquals('read', $filtered->action);
    }

}

/**
 * Mock class for tests
 */
class MyPages extends Controller
{
    public static $call = 0;
    public static $foo = 0;
    public static $bar = 0;

    public function start($id = -1)
    {
        self::$call = $id;
    }

    /**
     * @protected
     */
    public function callMe()
    {

    }

    /**
     * @before foo, bar
     * @after foo, bar
     */
    public function someAction()
    {

    }

    /**
     * @once
     */
    public function foo()
    {
        self::$foo += 1;
    }

    public function bar()
    {
        self::$bar += 1;
    }
}