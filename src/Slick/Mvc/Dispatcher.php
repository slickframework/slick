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
     * @readwrite
     * @var View
     */
    protected $_view;

    /**
     * @readwrite
     * @var View
     */
    protected $_layout;

    /**
     * Dispatches the routed request
     *
     * @param RouteInfo $routeInfo
     * @return Response
     */
    public function dispatch(RouteInfo $routeInfo)
    {
        $this->setRouteInfo($routeInfo);
        $method = $this->_routeInfo->getAction();
        $controller = $this->getController($method);
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
        $arguments = is_array($this->_routeInfo->getArguments()) ?
            $this->_routeInfo->getArguments() : [];

        $controller->arguments = $arguments;
        call_user_func_array(
            array(
                $controller,
                $method
            ),
            $arguments
        );

        $hooks($methodMeta, "@after");
        $this->_controller = $controller;

        return $this->_render();
    }

    /**
     * Loads the controller
     *
     * @param string $method
     * @return Controller
     */
    public function getController($method)
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
            $instance = new $className($options);
            $methodExists = method_exists($instance,$method);
            $this->_controller = $instance;
            if (!$methodExists && $instance->isScaffold()) {
                $this->_controller = Scaffold::getScaffoldController(
                    $instance
                );
            }
        }
        return $this->_controller;
    }

    /**
     * Render the view and layout templates with controller data
     *
     * @return Response
     */
    protected function _render()
    {
        $response = $this->_application->response;
        $body = null;
        $this->_controller->set(
            'flashMessages', $this->_controller->flashMessages
        );
        $data = $this->_controller->getViewVars();

        $doLayout = $this->_controller->renderLayout && $this->getLayout();
        $doView = $this->_controller->renderView && $this->getView();

        try {

            if ($doView) {
                $body = $this->getView()
                    ->set($data)
                    ->render();
            }

            if ($doLayout) {
                $body = $this->getLayout()
                    ->set('layoutData', $body)
                    ->set($data)
                    ->render();
            }

        } catch (\Exception $exp) {
            throw new Exception\RenderingErrorException(
                "Error while rendering view: " . $exp->getMessage()
            );
        }

        return $response->setContent($body);
    }

    /**
     * Returns the view for current request
     *
     * @return View
     */
    public function getView()
    {
        if (is_null($this->_view)) {
            $controller = $this->_routeInfo->controllerName;
            $name = $this->_routeInfo->action;
            $ext = $this->_routeInfo->getExtension();
            $template = "{$controller}/{$name}.{$ext}.twig";
            if (!is_null($this->_controller->view)) {
                $template = "{$this->_controller->view}.{$ext}.twig";
            }
            $this->_view = new View(['file' => $template]);
        }
        return $this->_view;
    }


    /**
     * Returns the layout for current request
     *
     * @return View
     */
    public function getLayout()
    {
        if (is_null($this->_layout)) {
            $default = 'layouts/default';
            $ext = $this->_routeInfo->getExtension();
            $file = "{$default}.{$ext}.twig";
            if (!is_null($this->_controller->layout)) {
                $file = "{$this->_controller->layout}.{$ext}.twig";
            }
            $this->_layout = new View(['file' => $file]);
        }
        return $this->_layout;
    }
}
