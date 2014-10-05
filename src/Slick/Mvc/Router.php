<?php

/**
 * Router
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use AltoRouter;
use Slick\Common\Base;
use Slick\Mvc\Events\Route;
use Slick\Mvc\Router\RouteInfo;
use Slick\Mvc\Exception\RouterException;
use Slick\Mvc\Exception\InvalidArgumentException;

/**
 * Router
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property-read AltoRouter $service
 * @property-read Application $application
 */
class Router extends Base
{
    /**#@+
     * Request methods
     * @var string
     */
    const METHOD_ALL    = 'GET|POST|PUT|DELETE';
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';
    /**#@-*/

    /**
     * @readwrite
     * @var Application
     */
    protected $_application;

    /**
     * Force application dependency
     *
     * @param Application $app
     * @param array $options
     */
    public function __construct(Application $app, array $options = [])
    {
        $options = array_merge(['application' => $app], $options);
        parent::__construct($options);
    }

    /**
     * @read
     * @var AltoRouter
     */
    protected $_service;

    /**
     * Returns the routing service
     *
     * @return AltoRouter
     */
    public function getService()
    {
        if (is_null($this->_service)) {
            $this->_service = new AltoRouter();
        }
        return $this->_service;
    }

    /**
     * Map a route to a target
     *
     * @param string $route The route regex, custom regex must start with an @.
     * You can use multiple pre-set regex filters, like [i:id]
     * @param mixed $defaults Static values to set as default
     * @param null|string $name Optional name of this route. Supply if you
     * want to reverse route this url in your application.
     * @param string $method One of 4 HTTP Methods, or a pipe-separated list
     * of multiple HTTP Methods (GET|POST|PUT|DELETE)
     *
     * @throws Exception\InvalidArgumentException If a named route already
     * exists in the router
     */
    public function map(
        $route, $defaults = [], $name = null, $method = self::METHOD_ALL)
    {
        try {
            $this->getService()->map($method, $route, $defaults, $name);
        } catch (\Exception $exp) {
            throw new InvalidArgumentException(
                "Fail to map a route: {$exp->getMessage()}",
                0,
                $exp
            );
        }
    }

    /**
     * Filter out route information
     *
     * @throws Exception\RouterException If no match was found in the router
     *
     * @return RouteInfo
     */
    public function filter()
    {
        $requestUrl = $this->application->request->getQuery('url', '/');
        $requestMethod = $this->application->request->getMethod();
        $event = new Route([
            'router' => $this,
            'application' => $this->application,
            'request' => $this->application->request
        ]);
        $this->_application->getEventManager()
            ->trigger(Route::BEFORE_ROUTE, $this, $event);
        $options = $this->getService()->match($requestUrl, $requestMethod);
        if ($options == false) {
            throw new RouterException(
                "No route was found for the given request URL."
            );
        }
        // Route match, add the extension
        $cfg = $this->application->getContainer()->get('configuration');


        $routerInfo = new RouteInfo($options);
        $routerInfo->setConfiguration($cfg)
            ->setTarget($options['target']);

        $extension = $this->application->getRequest()
            ->getQuery('extension');

        if (strlen($extension < 1)) {
            $extension = $cfg->get('router.extension', 'html');
        }
        $routerInfo->extension = $extension;

        $event->routeInfo = $routerInfo;
        $this->_application->getEventManager()
            ->trigger(Route::AFTER_ROUTE, $this, $event);
        return $event->routeInfo;
    }
}
