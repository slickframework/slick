<?php

/**
 * Route info
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Router;

use Slick\Common\Base;
use Slick\Configuration\Driver\DriverInterface;

/**
 * CRoute info
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property mixed $target The target set in the matched route
 * @property array $params
 * @property string $name
 * @property DriverInterface $configuration
 *
 * @property-read string $controller
 * @property-read string $action
 * @property-read string $namespace
 * @property-read array $arguments
 * @property-read string $extension
 * @property-read string $controllerName
 *
 * @method DriverInterface|null getConfiguration()
 * @method string getExtension()
 */
class RouteInfo extends Base
{

    /**
     * @readwrite
     * @var mixed
     */
    protected $_target;

    /**
     * @readwrite
     * @var array
     */
    protected $_params = [];

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @read
     * @var string
     */
    protected $_controller;

    /**
     * @read
     * @var string
     */
    protected $_action;

    /**
     * @read
     * @var array
     */
    protected $_arguments = [];

    /**
     * @read
     * @var string
     */
    protected $_namespace;

    /**
     * @readwrite
     * @var DriverInterface
     */
    protected $_configuration;

    /**
     * @readwrite
     * @var string
     */
    protected $_extension;

    /**
     * @read
     * @var string
     */
    protected $_controllerName;

    /**
     * Sets configuration driver
     *
     * @param DriverInterface $driver
     *
     * @return self
     */
    public function setConfiguration(DriverInterface $driver)
    {
        $this->_configuration = $driver;
        $this->_namespace = null;
        $this->_action = null;
        $this->_controller = null;
        return $this;
    }

    /**
     * Returns controller class name
     *
     * @return string
     */
    public function getController()
    {
        if (is_null($this->_controller)) {
            $controller = $this->getConfiguration()
                ->get('router.controller', 'pages');
            if (isset($this->_params['controller'])) {
                $controller = $this->_params['controller'];
            }
            $this->_controllerName = $controller;
            $this->_controller = trim(
                "{$this->namespace}\\". ucfirst($controller),
                '\\'
            );
        }
        return $this->_controller;
    }

    /**
     * Returns the namespace for controller class
     *
     * @return string
     */
    public function getNamespace()
    {
        if (is_null($this->_namespace)) {
            $namespace = $this->getConfiguration()
                ->get('router.namespace', 'Controllers');
            if (isset($this->_params['namespace'])) {
                $namespace = $this->_params['namespace'];
            }
            $this->_namespace = trim($namespace, '\\');
        }
        return $this->_namespace;
    }

    /**
     * Returns the controller action (method)
     *
     * @return string
     */
    public function getAction()
    {
        if (is_null($this->_action)) {
            $action =  $this->getConfiguration()
                ->get('router.action', 'index');
            if (isset($this->_params['action'])) {
                $action = $this->_params['action'];
            }
            $this->_action = $action;
        }
        return $this->_action;
    }

    /**
     * Returns the list of arguments passed in the URL
     *
     * @return array
     */
    public function getArguments()
    {
        if (empty($this->_arguments)) {
            $base = [];
            if (isset($this->_params['trailing'])) {
                $base = explode('/', $this->_params['trailing']);
            }
            $names = ['controller', 'action', 'namespace', 'trailing'];
            foreach ($this->_params as $key => $value) {
                if (in_array($key, $names)) {
                    continue;
                }
                $this->_arguments[$key] = $value;
            }

            $this->_arguments = array_merge($this->_arguments, $base);
        }
        return $this->_arguments;
    }

    /**
     * Returns the argument stored with the given name
     *
     * @param string  $name
     * @return null|string
     */
    public function getArgument($name)
    {
        $argument = null;
        if (isset($this->arguments[$name])) {
            $argument = $this->arguments[$name];
        }
        return $argument;
    }

    /**
     * Sets target data
     *
     * @param $target
     */
    public function setTarget($target)
    {
        if (is_array($target)) {
            $this->_params = array_merge($this->_params, $target);
        }
        $this->_target = $target;
    }

    /**
     * Returns the controller name for use with views
     *
     * @return string
     */
    public function getControllerName()
    {
        if (is_null($this->_controllerName)) {
            $this->getController();
        }
        return $this->_controllerName;
    }
}
