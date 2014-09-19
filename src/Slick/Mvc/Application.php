<?php

/**
 * MVC Application
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Di\Container;
use Slick\Di\Definition;
use Slick\Template\Template;
use Slick\Di\ContainerBuilder;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Slick\Configuration\Configuration;
use Slick\Configuration\Driver\DriverInterface;

/**
 * MVC Application
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Response $response HTTP response object
 * @property Request $request HTTP request object
 * @property Router $router HTTP request router
 * @property string $configFileName Configuration file name
 * @property string $configType Configuration driver type
 * @property Dispatcher $dispatcher Request dispatcher
 *
 * @method Application setResponse(Response $response) Sets the HTTP response
 * @method Application setRequest(Request $request) Sets the HTTP request
 * @method Application setContainer(Container $container) Sets the dependency
 * container object
 * @method Application setDispatcher(Dispatcher $dispatcher) Sets the
 * request dispatcher
 */
final class Application extends Base
{
    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @readwrite
     * @var Response
     */
    protected $_response;

    /**
     * @read
     * @var Router
     */
    protected $_router;

    /**
     * @readwrite
     * @var DriverInterface
     */
    protected $_configuration;

    /**
     * @readwrite
     * @var Container
     */
    protected $_container;

    /**
     * @readwrite
     * @var Dispatcher
     */
    protected $_dispatcher;

    /**
     * @readwrite
     * @var string
     */
    protected $_configFileName = 'config';

    /**
     * @readwrite
     * @var string
     */
    protected $_configType = 'php';

    /**
     * Bootstrap the application
     *
     * @returns Application
     */
    public function bootstrap()
    {
        $routesFile = "routes.php";
        $bootstrap = "bootstrap.php";
        $router = $this->getRouter();

        Template::addPath(
            getcwd() .'/'. $this->getConfiguration()
                ->get('paths.views', 'Views')
        );
        Template::appendPath(__DIR__ . '/Views');

        foreach (Configuration::getPathList() as $path) {
            if (is_file("{$path}/{$bootstrap}")) {
                include("{$path}/{$bootstrap}");
            }

            if (is_file("{$path}/{$routesFile}")) {
                include("{$path}/{$routesFile}");
            }
        }
    }

    /**
     * Runs the application
     *
     * @returns Response
     */
    public function run()
    {
        $routeInfo = $this->getRouter()->filter();
        return $this->dispatcher->dispatch($routeInfo);
    }

    /**
     * Lazy loads the response for current application request
     *
     * @return Response
     */
    public function getResponse()
    {
        if (is_null($this->_response)) {
            $this->_response = new Response();
        }
        return $this->_response;
    }

    /**
     * Lazy loads the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        if (is_null($this->_request)) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    /**
     *  Lazy loads the router object
     *
     * @return Router
     */
    public function getRouter()
    {
        if (is_null($this->_router)) {
            $this->_router = new Router($this);
        }
        return $this->_router;
    }

    /**
     * Returns the internal dependency injector container
     *
     * @return Container The dependency injector
     */
    public function getContainer()
    {
        if (is_null($this->_container)) {
            $def = [
                'configuration' => Definition::factory(
                    ['Slick\Configuration\Configuration', 'get'],
                    [$this->configFileName, $this->configType]
                )
            ];
            $this->setContainer(ContainerBuilder::buildContainer($def));
        }
        return $this->_container;
    }

    /**
     * Returns the configuration settings
     *
     * @return DriverInterface
     */
    public function getConfiguration()
    {
        if (is_null($this->_configuration)) {
            $config = $this->getContainer()->get('configuration');
            $this->_configuration = $config;
        }
        return $this->_configuration;
    }

    /**
     * Returns the request dispatcher
     *
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        if (is_null($this->_dispatcher)) {
            $this->setDispatcher(new Dispatcher(['application' => $this]));
        }
        return $this->_dispatcher;
    }
}
