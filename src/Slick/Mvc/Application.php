<?php

/**
 * Application
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Configuration\Configuration;
use Slick\Configuration\Driver\DriverInterface;
use Slick\Di\ContainerBuilder;
use Slick\Di\Definition;
use Slick\I18n\Translator;
use Slick\Template\Template;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * MVC Application
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Translator $translator
 */
class Application extends Base implements EventManagerAwareInterface
{

    /**
     * @readwrite
     * @var Router Application router
     */
    protected $_router;

    /**
     * @readwrite
     * @var EventManagerInterface Event manager
     */
    protected $_events;

    /**
     * @readwrite
     *
     * @var Response
     */
    protected $_response;

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @readwrite
     * @var DriverInterface
     */
    protected $_configuration;

    /**
     * @readwrite
     * @var MvcEvent
     */
    protected $_event;

    /**
     * @readwrite
     * @var Translator
     */
    protected $_translator;

    /**
     * Bootstrap the application
     *
     * @returns Application
     */
    public function bootstrap()
    {
        set_exception_handler(['\Slick\Mvc\Exception\Handler', 'handle']);
        set_error_handler(['\Slick\Mvc\Exception\Handler', 'handleError']);
        $router = $this->getRouter();
        $routesFile = "routes.php";
        $bootstrap = "bootstrap.php";
        $event = new MvcEvent('MvcEvent');
        $event->setRouter($router)
            ->setRequest($this->getRequest())
            ->setResponse($this->getResponse())
            ->setTarget($this);
        $this->_event = $event;

        Template::addPath(
            getcwd() .'/'. $this->getConfiguration()->get('paths.views', 'Views')
        );
        Template::appendPath(__DIR__ . '/Views');

        $this->getEventManager()
            ->trigger(MvcEvent::EVENT_BOOTSTRAP, $event);

        $this->translator = Translator::getInstance();

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
        $this->getRouter()->filter();
        $this->_event->setRouter($this->getRouter());

        $this->getEventManager()
            ->trigger(MvcEvent::EVENT_ROUTE, $this->_event);

        $this->getEventManager()
            ->trigger(MvcEvent::EVENT_DISPATCH, $this->_event);

        $this->_response = $this->_router->dispatch($this);
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     *
     * @return Application
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_class($this),
            )
        );
        $this->_events = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (is_null($this->_events)) {
            $container = ContainerBuilder::buildContainer(
                [
                    'DefaultEventManager' => Definition::object(
                            'Zend\EventManager\SharedEventManager'
                        )
                ]
            );
            $sharedEvents = $container->get('DefaultEventManager');
            $events = new EventManager();
            $events->setSharedManager($sharedEvents);
            $this->setEventManager($events);
        }
        return $this->_events;
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
     * Lazy loads the configuration driver
     *
     * @return DriverInterface
     */
    public function getConfiguration()
    {
        if (is_null($this->_configuration)) {
            $this->_configuration = Configuration::get('config');
        }
        return $this->_configuration;
    }

    /**
     * Lazy load of MVC application router
     *
     * @return Router
     */
    public function getRouter()
    {
        if (is_null($this->_router)) {
            $this->_router = new Router(['request' => $this->getRequest()]);
        }
        return $this->_router;
    }
}