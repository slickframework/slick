<?php

/**
 * AbstractRoute
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Router;

use Slick\Common\Base;

/**
 * AbstractRoute
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractRoute extends Base implements RouteInterface
{

    /**
     * @readwrite
     * @var string Route pattern
     */
    protected $_pattern;

    /**
     * @readwrite
     * @var string Correspondent controller
     */
    protected $_controller;

    /**
     * @readwrite
     * @var string Controller action/method
     */
    protected $_action;

    /**
     * @readwrite
     * @var array Request parameters
     */
    protected $_parameters = array();

    /**
     * @readwrite
     * @var string
     */
    protected $_namespace;

    /**
     * Returns the route pattern string
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->_pattern;
    }

    /**
     * Returns current parameters list
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_parameters;
    }

    /**
     * Sets current parameters
     *
     * @param array $params
     * @return AbstractRoute
     */
    public function setParams(array $params)
    {
        $this->_parameters = $params;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Returns the controller namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }


} 