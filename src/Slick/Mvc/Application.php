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
use Psr\Log\LoggerInterface;
use Slick\Log\Log;
use Slick\Template\Template;
use Slick\Di\ContainerBuilder;
use Slick\Mvc\Events\Dispatch;
use Slick\Mvc\Events\Bootstrap;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\EventManager\EventManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Slick\Configuration\Configuration;
use Zend\EventManager\EventManagerInterface;
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
 * @property LoggerInterface $logger PSR-3 logger
 * @property Run $whoops Error handler
 *
 * @method Application setResponse(Response $response) Sets the HTTP response
 * @method Application setRequest(Request $request) Sets the HTTP request
 * @method Application setContainer(Container $container) Sets the dependency
 * container object
 * @method Application setDispatcher(Dispatcher $dispatcher) Sets the
 * request dispatcher
 * @method Application setLogger(LoggerInterface $logger) Sets a PSR-3 logger
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
     * @readwrite
     * @var EventManagerInterface
     */
    protected $_events;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @read
     * @var Run
     */
    protected $_whoops;

    /**
     * Bootstrap the application
     *
     * @returns Application
     */
    public function bootstrap()
    {
        $this->_startErrorHandler();

        $routesFile = "routes.php";
        $bootstrap = "bootstrap.php";

        $router = $this->getRouter();

        $event = new Bootstrap([
            'router' => $router,
            'application' => $this
        ]);
        $this->getEventManager()
            ->trigger(Bootstrap::BEFORE_BOOTSTRAP, $this, $event);

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
        $this->getEventManager()
            ->trigger(Bootstrap::AFTER_BOOTSTRAP, $this, $event);
    }

    /**
     * Runs the application
     *
     * @returns Response
     */
    public function run()
    {
        $routeInfo = $this->getRouter()->filter();
        $event = new Dispatch([
            'application' => $this,
            'routeInfo' => $routeInfo
        ]);
        $this->getEventManager()
            ->trigger(Dispatch::BEFORE_DISPATCH, $this, $event);
        $response = $this->dispatcher->dispatch($routeInfo);
        $event->response = $response;
        $this->getEventManager()
            ->trigger(Dispatch::AFTER_DISPATCH, $this, $event);
        return $event->response;
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
                ),
                'sharedEventManager' => Definition::object(
                    'Zend\EventManager\SharedEventManager'
                )
            ];
            $this->setContainer(ContainerBuilder::buildContainer($def));
        }
        return $this->_container;
    }

    /**
     * Sets event manager
     *
     * @param EventManagerInterface $events
     *
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));
        $events->setSharedManager(
            $this->getContainer()->get("sharedEventManager")
        );
        $this->_events = $events;
        return $this;
    }

    /**
     * Returns the event manager
     *
     * @return mixed|EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->_events) {
            $this->setEventManager(new EventManager());
        }
        return $this->_events;
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

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (is_null($this->_logger)) {
            $this->_logger = Log::logger('Slick-Application');
        }
        return $this->_logger;
    }

    /**
     * Starts and registers the error handler
     *
     * @return self
     */
    protected function _startErrorHandler()
    {
        $this->_whoops = new Run();
        $environment = $this->getConfiguration()
            ->get('environment', 'production');

        if ($environment != 'production') {
            $handler = new PrettyPageHandler();
            $this->_whoops->pushHandler($handler);
        }

        $this->_whoops->register();
        return $this;
    }
}
