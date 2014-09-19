<?php

/**
 * Dispatch a routed request
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Mvc\Router\RouteInfo;
use Zend\Http\PhpEnvironment\Response;

/**
 * Dispatch a routed request
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property-write RouteInfo $routeInfo
 * @property-write Application $application
 *
 * @method Dispatcher setRouteInfo(RouteInfo $routeInfo)
 */
class Dispatcher extends Base
{

    /**
     * @write
     * @var RouteInfo
     */
    protected $_routeInfo;

    /**
     * @write
     * @var Application
     */
    protected $_application;

    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * Dispatches the routed request
     *
     * @param RouteInfo $routeInfo
     * @return Response
     */
    public function dispatch(RouteInfo $routeInfo)
    {
        $this->setRouteInfo($routeInfo);
        $controller = $this->getController();
        $method = $this->_routeInfo->getAction();
        $inspector = new Inspector($controller);
        $methodExists = $inspector->hasMethod($method);

        if (
            !$methodExists ||
            $inspector->getMethodAnnotations($method)
                ->hasAnnotation('@private') ||
            $inspector->getMethodAnnotations($method)
                ->hasAnnotation('@protected')
        ) {
            throw new Exception\ActionNotFoundException(
                "Action '{$method}' not found"
            );
        }
        $methodMeta = $inspector->getMethodAnnotations($method);


        /**
         * @param Inspector\AnnotationsList $meta
         * @param string $type
         */
        $hooks = function ($meta, $type) use ($inspector, $controller) {
            static $run;
            if (is_null($run)) {
                $run = array();
            }

            if ($meta->hasAnnotation($type)) {
                $methods = $meta->getAnnotation($type)->getValue();
                if (is_string($methods)) {
                    $methods = explode(
                        ', ',
                        $meta->getAnnotation($type)->getParameter('_raw')
                    );
                }
                foreach ($methods as $method) {
                    $hookMeta = $inspector->getMethodAnnotations($method);
                    if (
                        in_array($method, $run) &&
                        $hookMeta->hasAnnotation('@once')
                    ) {
                        continue;
                    }
                    $run[] = $method;
                    $controller->$method();
                }

            }
        };

        $hooks($methodMeta, "@before");

        call_user_func_array(
            array(
                $controller,
                $method
            ),
            is_array($this->_routeInfo->params) ?
                $this->_routeInfo->params : []
        );

        $hooks($methodMeta, "@after");
        $this->_controller = $controller;

        return $this->_render();
    }

    /**
     * Loads the controller
     *
     * @return Controller
     */
    public function getController()
    {
        if (is_null($this->_controller)) {
            $className = $this->_routeInfo->getController();
            if (!class_exists($className)) {
                throw new Exception\ControllerNotFoundException(
                    "Controller '{$className}' not found"
                );
            }

            $options = array(
                'request' => $this->_application->getRequest(),
                'response' => $this->_application->getResponse(),
            );

            /** @var Controller $instance */
            $this->_controller = new $className($options);
        }
        return $this->_controller;
    }

    protected function _render()
    {
        $response = $this->_application->response;
        $body = '';
        $this->_controller->set(
            'flashMessages', $this->_controller->flashMessages
        );



        return $response->setContent('Home page');
    }

} 