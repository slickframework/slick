<?php

/**
 * Router
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Configuration\Configuration;
use Slick\Configuration\Driver\DriverInterface;
use Slick\Mvc\Router\AbstractRoute;
use Slick\Mvc\Router\RouteInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Slick\Mvc\Router\Exception;

/**
 * Router
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Router extends Base
{

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * @readwrite
     * @var string
     */
    protected $_extension;

    /**
     * @readwrite
     * @var string
     */
    protected $_controller;

    /**
     * @readwrite
     * @var string
     */
    protected $_namespace;

    /**
     * @readwrite
     * @var string
     */
    protected $_action;

    /**
     * @readwrite
     * @var array
     */
    protected $_params = array();

    /**
     * @readwrite
     * @var RouteInterface[]
     */
    protected $_routes = array();

    /**
     * The configuration drive
     *
     * @var DriverInterface
     */
    protected $_configuration;

    /**
     * Adds a route to the list of defined routes.
     *
     * @param RouteInterface $route The route to add.
     *
     * @return Router The self instance for method chain calls.
     */
    public function addRoute(RouteInterface $route)
    {
        $this->_routes[] = $route;
        return $this;
    }

    /**
     * Removes a route from the list of defined routes.
     *
     * @param RouteInterface $route The route to remove.
     * @return Router The self instance for method chain calls.
     */
    public function removeRoute(RouteInterface $route)
    {
        foreach ($this->_routes as $i => $stored) {
            if ($stored == $route) {
                unset($this->_routes[$i]);
            }
        }
        return $this;
    }

    /**
     * Returns the list of available route patterns.
     *
     * @return array A key/value pairs of patterns and route class names.
     */
    public function getRoutes()
    {
        $list = array();
        foreach ($this->_routes as $route) {
            $list[$route->getPattern()] = get_class($route);
        }
        return $list;
    }

    /**
     * Loops thru all routes to find a match for the request string.
     *
     * If there are no routes it will assume the controller/action/param/param
     * format to inferred the controller, action and parameter to run.
     *
     * @return RouteInterface the matched route for this request
     */
    public function filter()
    {
        $url = $this->_request->getQuery('url');
        $parameters = array();
        $controller = $this->getConfiguration()->get('controller', "pages");
        $action = $this->getConfiguration()->get('action', "index");
        $namespace = $this->getConfiguration()
            ->get('namespace', 'Controllers');
        $matched = false;

        /** @var AbstractRoute $route */
        foreach ($this->_routes as $route) {
            $matches = $route->matches($url);
            if ($matches) {
                $controller = $route->getController();
                $action = $route->getAction();
                $parameters = $route->getParams();
                $namespace = $route->getNamespace();
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            $parts = explode("/", trim($url, "/"));

            if (sizeof($parts) > 0 ) {
                $controller = $parts[0];

                if (sizeof($parts) >= 2) {
                    $action = $parts[1];
                    $parameters = array_slice($parts, 2);
                }
            }
        }

        $this->_action = $action;
        $this->_controller = $controller;
        $this->_params = $parameters;
        $this->_namespace = $namespace;

        return $this;
    }

    /**
     * Dispatches the request
     *
     * @param Application $app The context application
     *
     * @throws Router\Exception\ControllerNotFoundException
     * @throws Router\Exception\ActionNotFoundException
     *
     * @returns Response The response object for this request
     */
    public function dispatch(Application $app)
    {
        $name = $this->_namespace .'\\'. ucfirst($this->_controller);

        if (!class_exists($name)) {
            throw new Exception\ControllerNotFoundException(
                "Controller {$this->_controller} not found"
            );
        }

        $instance = new $name(
            array(
                'parameters' => $this->_params,
                'extension' => $this->getExtension()
            )
        );

        if (!method_exists($instance, $this->_action)) {
            throw new Exception\ActionNotFoundException(
                "Action {$this->_action} not found"
            );
        }

        $inspector = new Inspector($instance);
        $methodMeta = $inspector->getMethodMeta($this->_action);

        if (
            !empty($methodMeta['@protected']) ||
            !empty($methodMeta['@private'])
        ) {
            throw new Exception\ActionNotFoundException(
                "Action {$this->_action} not found"
            );
        }

        $hooks = function ($meta, $type) use ($inspector, $instance) {
            static $run;
            if (is_null($run)) {
                $run = array();
            }
            if (isset($meta[$type])) {

                foreach ($meta[$type] as $method) {
                    $hookMeta = $inspector->getMethodMeta($method);
                    if (
                        in_array($method, $run) &&
                        !empty($hookMeta['@once'])
                    ) {
                        continue;
                    }
                    $run[] = $method;
                    $instance->$method();
                }

            }
        };

        $hooks($methodMeta, "@before");

        call_user_func_array(
            array(
                $instance,
                $this->_action
            ),
            is_array($this->_params) ? $this->_params : array()
        );

        $hooks($methodMeta, "@after");
    }

    /**
     * Sets the configuration driver
     *
     * @param DriverInterface $configuration
     *
     * @return Router
     */
    public function setConfiguration($configuration)
    {
        $this->_configuration = $configuration;
        return $this;
    }

    /**
     * Lazy loading of configuration driver
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
     * Returns current extension
     * @return string
     */
    public function getExtension()
    {
        if (is_null($this->_extension)) {
            $ext = $this->getConfiguration()->get('extension', 'html');
            $this->_extension = $this->_request->getQuery('extension', $ext);
        }
        return $this->_extension;
    }


} 